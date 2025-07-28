<?php

namespace App\Controller;

use App\Service\UserResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserResponseController extends AbstractController
{
    #[Route('/user-response/first-question', name: 'get_first_question', methods: ['GET'])]
    public function firstQuestion(Request $request, UserResponseService $userResponse): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }

        $question = $userResponse->getFirstQuestion($user);

        return $this->json($question);
    }

    #[Route('/user-response/next-question/{questionId}', name: 'get_next_question', methods: ['POST'])]
    public function nextQuestion($questionId, Request $request, UserResponseService $userResponse): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        if(!isset($data['answer'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        $answer = $data['answer'];

        $array = $userResponse->getNextQuestion($questionId, $answer, $user);

        $code = $array['code'];
        unset($array['code']);

        return $this->json($array, $code);
    }
}
