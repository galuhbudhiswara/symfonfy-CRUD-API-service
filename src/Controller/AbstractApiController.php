<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function PHPSTORM_META\type;

abstract class AbstractApiController extends AbstractFOSRestController
{
   protected function buildForm(String $type, $data = null, array $options = []): FormInterface
    {
        $options = array_merge($options, [
            'csrf_protection' => false,
        ]);

    return $this->container->get('form.factory')->createdName('', $type, $data, $options);
   }
   protected function respond($data, int $statusCode = Response::HTTP_OK): Response
   {
    return $this->handleView($this->view($data, $statusCode));
   }
}
