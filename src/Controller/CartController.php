<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Form\CartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractApiController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // #[Route('/cart', name: 'app_cart')]
    public function show(Request $request): Response
    {
        $customerId = $request->get('id');
        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy([
            'customer' => $customerId
        ]);

        if (!$customer) {
            throw new NotFoundHttpException("Cart not found");
        }

        $carts = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'customer' => $customer
        ]);

        if (!$carts) {
            throw new NotFoundHttpException("Card does not exist for this customer");
        }

        return $this->respond($carts);
    }

    public function create(Request $request) : Response 
    {
        $form = $this->buildForm(CartType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var Cart $cart
         */
        $cart = $form->getData();

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $this->respond($cart);   
    }

    public function delete(Request $request) : Response 
    {
        $cartId = $request->get('cartId');
        $customerId = $request->get('customerId');
        $cart = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'customer' => $customerId,
            'id' => $cartId
        ]);

        if (!$cart) {
            throw new NotFoundHttpException('Cart not found');
        }

        $this->entityManager->remove($cart);
        $this->entityManager->flush();

        return $this->respond(null);
    }

    public function update(Request $request) : Response 
    {
        $customerId = $request->get('customerId');
        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy([
            'customer' => $customerId
        ]);

        if (!$customer) {
            throw new NotFoundHttpException("Cart not found");
        }

        $carts = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'customer' => $customer
        ]);

        $form = $this->buildForm(CartType::class, $carts, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var Cart $cart
         */
        $cart = $form->getData();

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $this->respond($cart);   
    }
}