<?php

namespace App\Controller;

use App\Service\UserResponseV2Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserResponseV2Controller extends AbstractController
{
    #[Route('/user-response/v2/first-question', name: 'get_first_question', methods: ['GET'])]
    public function firstQuestion(Request $request, UserResponseV2Service $userResponseV2): JsonResponse
    {
        // $user = $this->getUser();
        // if (!$user) {
        //     throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        // }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

        // $data = $request->getContent();
        // $data = json_decode($data, true);

        // if(!isset($data['source']) || 
        // !isset($data['target']) ||
        // !isset($data['typeTarget']) ||
        // !isset($data['answer'])) {
        //     $response = ["error" => "Missing informations"];
        //     return $this->json($response, Response::HTTP_BAD_REQUEST);
        // }

        // $source = $data['source'];
        // $target = $data['target'];
        // $typeTarget = $data['typeTarget'];
        // $answer = $data['answer'];

        // $relation = $relationService->join($source, $target, $typeTarget, $answer);

        $question = $userResponseV2->getFirstQuestion();


        return $this->json($question);
    }

    #[Route('/user-response/v2/next-question/{questionId}', name: 'get_next_question', methods: ['POST'])]
    public function nextQuestion($questionId, Request $request, UserResponseV2Service $userResponseV2): JsonResponse
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

        if(!isset($data['answer'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        $answer = $data['answer'];

        // $relation = $relationService->join($source, $target, $typeTarget, $answer);

        // $question = $userResponseV2->getFirstQuestion();

        $question = $userResponseV2->getNextQuestion($questionId, $answer);

        return $this->json($question);
    }
}
