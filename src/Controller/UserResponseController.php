<?php

namespace App\Controller;

use App\Entity\UserResponse;
use App\Repository\UserResponseRepository;
use App\Repository\TemplateQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserResponseController extends AbstractController
{
    #[Route('/user-response/new/{id}', name: 'new_user_question')]
    public function duplicate($id, JWTTokenManagerInterface $tokenManager, UserResponseRepository $userResponseRepository, TemplateQuestionRepository $TQRepository): JsonResponse
    {
        $user = $this->getUser();

        if(!$user){
            $response = ["error" => "User incorect"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $question = $TQRepository->find($id);

        if(!$question){
            $response = ["error" => "Question not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $duplicate = $userResponseRepository->findBy(["User" => $user, "Question" => $question]);

        if($duplicate){
            $response = ["error" => "Duplicate"];
            return $this->json($response, Response::HTTP_UNAUTHORIZED);
        }

        try{
            $response = new UserResponse;

            $response->duplicate($question);
            $response->setUser($user);
            $response->setQuestion($question);

            $userResponseRepository->add($response, true);

            return $this->json($response);

        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
    
        // return $this->json($user);
    }
}
