<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\EventType;
use App\Entity\Organization;
use App\Enum\EventPaymentConditionEnum;
use App\Repository\EventTypeRepository;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/event-type')]
class EventTypeController extends AbstractController
{
    public function __construct(
        private EventTypeRepository $repository,
        private EntityManagerInterface $em
    )
    { }

    #[Route(name: 'event_type.list', methods: ['GET'])]
    public function list(#[MapQueryString] ?PaginationDTO $paginationDTO): JsonResponse
    {
        $eventTypes = $this->repository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);

        return $this->json($eventTypes, JsonResponse::HTTP_OK, [], ['groups' => 'event_type.read']);
    }

    #[Route('/{id}', name: 'event_type.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(EventType $eventType): JsonResponse
    {
        return $this->json($eventType, JsonResponse::HTTP_OK, [], ['groups' => 'event_type.read']);
    }

    #[Route('/{id}', name: 'event_type.create', methods: ['POST'], requirements: ['id' =>  Requirement::DIGITS])]
    public function create(
        #[MapRequestPayload(serializationContext:['groups' => ['event_type.create']])] EventType $eventType,
        Organization $organization,
    ): JsonResponse
    {
        $eventType->setOrganization($organization);

        if ($eventType->getReservationPaymentCondition() !== EventPaymentConditionEnum::FREE) {
            if ($eventType->getPrice() === 0) {
                return $this->json('The price must be greater than 0 if the reservation payment condition is not free', JsonResponse::HTTP_BAD_REQUEST);
            }

            if ($eventType->getDepositAmount() === 0 && $eventType->getReservationPaymentCondition() === EventPaymentConditionEnum::DEPOSIT) {
                return $this->json('The deposit amount must be greater than 0 if the reservation payment condition is not free', JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        $this->em->persist($eventType);
        $this->em->flush();

        return $this->json($eventType, JsonResponse::HTTP_CREATED, [], ['groups' => 'event_type.read']);
    }

    #[Route('/{id}', name: 'event_type.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        Request $request,
        EventType $eventType,
        SerializerInterface $serializer,
        ValidationService $validationService    
    ): JsonResponse
    {
        $serializer->deserialize($request->getContent(), EventType::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $eventType,
            'groups' => ['event_type.update']
        ]);

        if ($eventType->getReservationPaymentCondition() !== EventPaymentConditionEnum::FREE) {
            if ($eventType->getPrice() === 0) {
                return $this->json('The price must be greater than 0 if the reservation payment condition is not free', JsonResponse::HTTP_BAD_REQUEST);
            }

            if ($eventType->getDepositAmount() === 0 && $eventType->getReservationPaymentCondition() === EventPaymentConditionEnum::DEPOSIT) {
                return $this->json('The deposit amount must be greater than 0 if the reservation payment condition is not free', JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        $errors = $validationService->validate($eventType);
        if ($errors) return $errors;

        $this->em->persist($eventType);
        $this->em->flush();

        return $this->json($eventType, JsonResponse::HTTP_OK, [], ['groups' => 'event_type.read']); 
    }

    #[Route('/{id}', name: 'event_type.delete', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS])]
    public function delete(EventType $eventType): JsonResponse
    {
        $this->em->remove($eventType);
        $this->em->flush();

        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
