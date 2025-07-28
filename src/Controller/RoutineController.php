<?php

namespace App\Controller;

use App\Service\RoutineService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class RoutineController extends AbstractController
{
    #[Route('/routine/show/{routine}', name: 'show_routine', methods: ['GET'])]
    public function show($routine, Request $request, RoutineService $routineService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $routine = $routineService->show($routine);

        return $this->json($routine, Response::HTTP_OK);
    }

    #[Route('/routine/list', name: 'list_routine', methods: ['GET'])]
    public function list(Request $request, RoutineService $routineService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $routine = $routineService->list();

        return $this->json($routine, Response::HTTP_OK);
    }

    #[Route('/routine/create', name: 'create_routine', methods: ['POST'])]
    public function create(Request $request, RoutineService $routineService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        if(!isset($data['name']) || 
        !isset($data['description']) ||
        !isset($data['days']) ||
        !isset($data['taskTime'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {

            $routine = $routineService->new($data['name'], $data['description'], $data['days'], $data['taskTime']);

            return $this->json($routine[0], $routine[1]);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/routine/edit/{routine}', name: 'edit_routine', methods: ['PATCH'])]
    public function edit($routine, Request $request, RoutineService $routineService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        try {

            $routine = $routineService->edit($routine, $data);

            return $this->json($routine[0], $routine[1]);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
