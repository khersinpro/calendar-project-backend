<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\DTO\ScheduleEvent\CreateScheduleEventDTO;
use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\EventType;
use App\Entity\Schedule;
use App\Entity\ScheduleEvent;
use App\Enum\EventPaymentConditionEnum;
use App\Repository\CountryRepository;
use App\Repository\CustomerRepository;
use App\Repository\EventTypeRepository;
use App\Repository\ScheduleEventRepository;
use App\Repository\ScheduleRepository;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use App\Enum\ScheduleEventStatusEnum;
use GuzzleHttp\Psr7\Request;

#[Route('/api/schedule-event')]
class ScheduleEventController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {}

    #[Route(name: 'schedule_event.list', methods: ['GET'])]
    public function list(
        #[MapQueryString] ?PaginationDTO $paginationDTO,
        ScheduleEventRepository $scheduleEventRepository
    )
    {
        $scheduleEvents = $scheduleEventRepository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);

        return $this->json($scheduleEvents, JsonResponse::HTTP_OK, [], ['groups' => 'schedule_event.read']);
    }

    #[Route('/{id}', name: 'schedule_event.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(ScheduleEvent $scheduleEvent): JsonResponse
    {
        return $this->json($scheduleEvent, JsonResponse::HTTP_OK, [], ['groups' => 'schedule_event.read']);
    }

    #[Route(name: 'schedule_event.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateScheduleEventDTO $data,
        ScheduleRepository $scheduleRepository,
        EventTypeRepository $eventTypeRepository,
        CustomerRepository $customerRepository,
        CountryRepository $countryRepository,
        ValidationService $validationService
    ): JsonResponse
    {
        /** @var Schedule|null $schedule */
        $schedule = $scheduleRepository->findOneBy(['id' => $data->schedule_id]);

        if (!$schedule) {
            return $this->json('Schedule not found', JsonResponse::HTTP_NOT_FOUND);
        }

        $organizationUser = $schedule->getOrganizationUser();

        /** @var EventType|null $eventType */
        $eventType = $eventTypeRepository->findOneBy(['id' => $data->event_type_id]);
        
        if (!$eventType) {
            return $this->json('Event type not found', JsonResponse::HTTP_NOT_FOUND);
        }

        if ($eventType->getOrganization() !== $organizationUser->getOrganization()) {
            return $this->json(
                'The event type must be in the same organization as the organization user', 
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!$organizationUser->getEventTypes()->contains($eventType)) {
            return $this->json(
                'The event type is already assigned to the organization user', 
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        /** @var Customer|null $customer */
        $customer = $customerRepository->findOneBy(['email' => $data->email]);
        
        if (!$customer) {
            $custom = new Customer();
            $custom->setEmail($data->email);
            $custom->setFirstname($data->firstname);
            $custom->setLastname($data->lastname);
            $custom->setPhone($data->phone);
            $customerRepository->save($custom);
        }

        $scheduleEvent = new ScheduleEvent();
        $scheduleEvent->setStartDate($data->start_date);

        // TODO: set end date based on the event type duration and the start date
        $scheduleEvent->setEndDate($data->start_date);


        $scheduleEvent->setCustomer($customer);
        $scheduleEvent->setEventType($eventType);
        $scheduleEvent->setSchedule($schedule);

        if ($customer->getFirstname() !== $data->firstname || $customer->getLastname() !== $data->lastname) {
            $scheduleEvent->setGuestFirstName($data->firstname);
            $scheduleEvent->setGuestLastName($data->lastname);
            $scheduleEvent->setGuestPhone($data->phone);
        }

        if ($eventType->isAddressRequired()) {
            if ($data->address && $data->postal_code && $data->city && $data->country) {
                $address = new Address();
                $address->setAddress($data->address);
                $address->setPostalCode($data->postal_code);
                $address->setCity($data->city);

                /** @var Country|null $country */
                $country = $countryRepository->findOneBy(['name' => $data->country]);

                $address->setCountry($country);

                $errors = $validationService->validate($address);
                if ($errors) return $errors;

                $scheduleEvent->setAdress($address);
            } else {
                return $this->json('The address is required', JsonResponse::HTTP_BAD_REQUEST);
            }
        }
        
        $status = 
            $eventType->getReservationPaymentCondition() === EventPaymentConditionEnum::PAYMENT || 
            $eventType->getReservationPaymentCondition() === EventPaymentConditionEnum::DEPOSIT
            ? ScheduleEventStatusEnum::PENDING 
            : ScheduleEventStatusEnum::CONFIRMED
        ;

        $scheduleEvent->setStatus($status);

        $this->em->persist($scheduleEvent);
        $this->em->flush();

        return $this->json($scheduleEvent, JsonResponse::HTTP_CREATED, [], ['groups' => 'schedule_event.read']);
    }

    #[Route('/{id}', name: 'schedule_event.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        ScheduleEvent $scheduleEvent,
        Request $request,
    ): JsonResponse
    {
        // TODO: update the schedule event
        return $this->json($scheduleEvent, JsonResponse::HTTP_OK, [], ['groups' => 'schedule_event.read']);
    }

    #[Route('/{id}', name: 'schedule_event.delete', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS])]
    public function delete(ScheduleEvent $scheduleEvent): JsonResponse
    {
        // TODO: Create VOTERS
        $this->em->remove($scheduleEvent);
        $this->em->flush();

        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/payment', name: 'schedule_event.payment', methods: ['POST'])]
    public function payment(): JsonResponse
    {
        // TODO: IMPLEMENT WHEN STRIPE IS READY
        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
