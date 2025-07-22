<?php

namespace App\Controller;
use App\Service\RoutineService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;

class RoutineController extends AbstractController
{
    public function __construct(
        private readonly RoutineService $routineService,
    ) {}
    // NOT USED
    #[Route('/routine', name: 'app_routine')]
    public function index(): Response
    {
        return $this->render('routine/index.html.twig', [
            'controller_name' => 'RoutineController',
        ]);
    }

    // USED
    #[Route('/routine/get-by-user', name: 'routine_by_user', methods: ['GET'])]
    public function getRoutinesByUser(): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }
        $tasks = $this->routineService->controllerGetRoutineByUser($user->getId());
        return $this->json($tasks, Response::HTTP_OK);
    }
}
