<?php

namespace App\Controller;

use App\Repository\RoutineRepository;
use App\Service\RoutineService;
use App\Service\UserRoutineV2Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserRoutineV2Controller extends AbstractController
{

    #[Route('/routine/v2/get-by-user', name: 'routine_by_user', methods: ['GET'])]
    public function getRoutinesByUser(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        // RoutineRepository $routineRepository,
        UserRoutineV2Service $userRoutineV2Service
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

        try {
            $tasks = $userRoutineV2Service->controllerGetRoutineByUser($user->id);

            // $tasks = $routineRepository->findBy(['User' => $user->id]);

            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'routine:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
