<?php

namespace App\Controller;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthenticationController extends AbstractController
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}
    #[Route('/user/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $userDto = $this->authService->controllerRegister($request);
        return $this->json($userDto, Response::HTTP_CREATED);
    }
    #[Route('/user/authenticate', name: 'user_authenticate', methods: ['POST'])]
    public function authenticate(Request $request): JsonResponse
    {
        
        $authResult = $this->authService->controllerAuthenticate($request);
        return $this->json([
            'token' => $authResult['token'],
            'user' => get_object_vars($authResult['user']),
        ], Response::HTTP_OK);
    }
    
}
