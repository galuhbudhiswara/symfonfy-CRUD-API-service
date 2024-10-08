<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends AbstractApiController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // #[Route('/customer', name: 'app_customer')]
    public function index(Request $request): JsonResponse
    {
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();

        return $this->respond($customers);
    }

    public function create(Request $request) : JsonResponse 
    {
        $form = $this->buildForm(CustomerType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var Customer $customer
         */
        $customer = $form->getData();

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $this->respond($customer);   
    }
}