<?php

namespace App\Controller;

use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class QuestionController extends AbstractController
{
    #[Route('/question/show/{question}', name: 'show_question', methods: ['GET'])]
    public function show($question, Request $request, QuestionService $questionService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $question = $questionService->show($question);

        return $this->json($question, Response::HTTP_OK);
    }

    #[Route('/question/list', name: 'list_question', methods: ['GET'])]
    public function list(Request $request, QuestionService $questionService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $question = $questionService->list();

        return $this->json($question, Response::HTTP_OK);
    }

    #[Route('/question/list-root', name: 'list_root_question', methods: ['GET'])]
    public function listRoot(Request $request, QuestionService $questionService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $question = $questionService->listRoot();

        return $this->json($question, Response::HTTP_OK);
    }
    
    #[Route('/question/create', name: 'create_question', methods: ['POST'])]
    public function create(Request $request, QuestionService $questionService): JsonResponse
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
        !isset($data['content']) ||
        !isset($data['rootQuestion']) ||
        !isset($data['quickQuestion'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {

            $question = $questionService->new($data['name'], $data['content'], $data['rootQuestion'], $data['quickQuestion']);

            return $this->json($question[0], $question[1]);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/question/edit/{question}', name: 'edit_question', methods: ['PATCH'])]
    public function edit($question, Request $request, QuestionService $questionService): JsonResponse
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

            $question = $questionService->edit($question, $data);

            return $this->json($question[0], $question[1]);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
