<?php

namespace App\Controller;

use App\Service\QuestionV2Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class QuestionV2Controller extends AbstractController
{
    #[Route('/question/v2/show/{question}', name: 'show_question_v2', methods: ['GET'])]
    public function show($question, Request $request, QuestionV2Service $questionService): JsonResponse
    {
        // $user = $this->getUser();
        // if (!$user) {
        //     throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        // }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

        $questionV2 = $questionService->show($question);

        return $this->json($questionV2, Response::HTTP_OK);
    }

    #[Route('/question/v2/list', name: 'list_question_v2', methods: ['GET'])]
    public function list(Request $request, QuestionV2Service $questionService): JsonResponse
    {
        // $user = $this->getUser();
        // if (!$user) {
        //     throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        // }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

        $questionV2 = $questionService->list();

        return $this->json($questionV2, Response::HTTP_OK);
    }
    
    #[Route('/question/v2/create', name: 'create_question_v2', methods: ['POST'])]
    public function create(Request $request, QuestionV2Service $questionService): JsonResponse
    {
        // $user = $this->getUser();
        // if (!$user) {
        //     throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        // }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

        $data = $request->getContent();
        $data = json_decode($data, true);

        if(!isset($data['name']) || 
        !isset($data['content']) ||
        !isset($data['rootQuestion']) ||
        !isset($data['quickQuestion'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {

            $questionV2 = $questionService->new($data['name'], $data['content'], $data['rootQuestion'], $data['quickQuestion']);

            return $this->json($questionV2[0], $questionV2[1]);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/question/v2/edit/{question}', name: 'edit_question_v2', methods: ['PATCH'])]
    public function edit($question, Request $request, QuestionV2Service $questionService): JsonResponse
    {
        // $user = $this->getUser();
        // if (!$user) {
        //     throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        // }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

        $data = $request->getContent();
        $data = json_decode($data, true);

        try {

            $questionV2 = $questionService->edit($question, $data);

            return $this->json($questionV2[0], $questionV2[1]);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
