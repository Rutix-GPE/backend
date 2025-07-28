<?php

namespace App\Controller;

use App\Repository\RoutineRepository;
use App\Service\RoutineService;
use App\Service\UserRoutineService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserRoutineController extends AbstractController
{

    #[Route('/routine/get-by-user', name: 'routine_by_user', methods: ['GET'])]
    public function getRoutinesByUser(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        UserRoutineService $userRoutineService
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }

        try {
            $tasks = $userRoutineService->controllerGetRoutineByUser($user->id);

            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'routine:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
