<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractApiController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // #[Route('/customer', name: 'app_customer')]
    public function index(Request $request): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->respond($products);
    }

    public function create(Request $request) : JsonResponse 
    {
        $form = $this->buildForm(ProductType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var Product $customer
         */
        $product = $form->getData();

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->respond($product);   
    }
}