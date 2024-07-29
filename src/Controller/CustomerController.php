<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/customer')]
class CustomerController extends AbstractController
{
    #[Route(name: 'app_customer')]
    public function index(
        #[MapQueryString] ?PaginationDTO $paginationDTO,
        CustomerRepository $customerRepository
    ): JsonResponse
    {
        $customers = $customerRepository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);

        return $this->json($customers, JsonResponse::HTTP_OK, [], ['groups' => 'customer.read']);
    }

    #[Route('/{id}', name: 'app_customer_show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(Customer $customer): JsonResponse
    {
        return $this->json($customer, JsonResponse::HTTP_OK, [], ['groups' => 'customer.read']);
    }
}
