<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class testController extends AbstractController
{
    #[Route('/test/test', name: 'test', methods: ['GET'])]
    public function test(Request $request):JsonResponse
    {
        $test = "maya";
        return $this->json($test, Response::HTTP_OK);
    }
  

}
