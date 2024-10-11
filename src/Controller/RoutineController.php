<?php

namespace App\Controller;

use App\Repository\RoutineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoutineController extends AbstractController
{
    #[Route('/routine', name: 'app_routine')]
    public function index(): Response
    {
        return $this->render('routine/index.html.twig', [
            'controller_name' => 'RoutineController',
        ]);
    }

    #[Route('/routine/get-by-user', name: 'routine_by_user', methods: ['GET'])]
    public function getTasksByUser(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        RoutineRepository $routineRepository
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        try {

            $tasks = $routineRepository->findBy(['User' => $user->id]);

            return $this->json($tasks, Response::HTTP_OK);

            // return $this->json($userResponse, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
