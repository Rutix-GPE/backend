<?php

namespace App\Controller;

use App\Entity\UserResponse;
use App\Repository\UserResponseRepository;
use App\Repository\TemplateQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserResponseController extends AbstractController
{

    //ToDo 
    #[Route('/user-response/question/{questionId}', name: 'get_response_by_question_id', methods: ['GET'])]
    public function getResponseByQuestionId(
        $questionId,
        JWTTokenManagerInterface $tokenManager,
        UserResponseRepository $userResponseRepository,
        TemplateQuestionRepository $questionRepository
    ): JsonResponse
    {
        // Récupérer l'utilisateur authentifié via JWT
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        // Rechercher la question
        $question = $questionRepository->find($questionId);
        if (!$question) {
            return $this->json(['error' => 'Question not found'], Response::HTTP_NOT_FOUND);
        }

        // Rechercher la réponse de l'utilisateur pour cette question (par relations User et Question)
        $userResponse = $userResponseRepository->findOneBy([
            'user' => $user,        // Rechercher par l'entité User
            'question' => $question // Rechercher par l'entité Question
        ]);

        if (!$userResponse) {
            return $this->json(['error' => 'Response not found for this question'], Response::HTTP_NOT_FOUND);
        }

        // Retourner la réponse si elle existe
        return $this->json($userResponse, Response::HTTP_OK);
    }


    #[Route('/user-response/new/{id}', name: 'new_user_question', methods: ['POST'])]
    public function newUserQuestion(
        $id,
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        UserResponseRepository $userResponseRepository,
        TemplateQuestionRepository $TQRepository
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $question = $TQRepository->find($id);
        if (!$question) {
            return $this->json(['error' => 'Question not found'], Response::HTTP_NOT_FOUND);
        }

        // Vérification des doublons
        $duplicate = $userResponseRepository->findBy([
            'user' => $user,
            'question' => $question
        ]);

        if ($duplicate) {
            return $this->json(['error' => 'Duplicate'], Response::HTTP_CONFLICT);
        }

        try {
            // Récupérer le champ 'response' envoyé dans la requête
            $requestData = $request->toArray(); // On suppose que les données sont envoyées en JSON
            $userResponseContent = $requestData['response'] ?? null;

            if (!$userResponseContent) {
                return $this->json(['error' => 'Response content is missing'], Response::HTTP_BAD_REQUEST);
            }

            // Créer une nouvelle réponse utilisateur
            $userResponse = new UserResponse();

            // Appel à la méthode duplicate avant de définir le reste
            $userResponse->duplicate($question);

            // Assigner l'utilisateur, la question et la réponse
            $userResponse->setUser($user);
            $userResponse->setQuestion($question);
            $userResponse->setResponse($userResponseContent); // Stocker le champ 'response'

            // Sauvegarder la réponse
            $userResponseRepository->add($userResponse, true);

            return $this->json($userResponse, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/user-response/new/page/{id}', name: 'new_user_question_page')]
    public function duplicateByPage($id, JWTTokenManagerInterface $tokenManager, UserResponseRepository $userResponseRepository, TemplateQuestionRepository $TQRepository)
    {
        $arrayResponse = [];
        $user = $this->getUser();

        if(!$user){
            $response = ["error" => "User incorect"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $question = $TQRepository->findBy(['page' => $id]);

        if(!$question){
            $response = ["error" => "Question not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        foreach($question as $val){

            $duplicate = $userResponseRepository->findBy(["User" => $user, "Question" => $val]);

            if(!$duplicate){
                try{
                    $response = new UserResponse;

                    $response->duplicate($val);
                    $response->setUser($user);
                    $response->setQuestion($val);
        
                    $userResponseRepository->add($response, true);

                    array_push($arrayResponse, $response);

                } catch (\Exception $error) {
                    $response = ["error" => $error->getMessage()];
                    return $this->json($response, Response::HTTP_BAD_REQUEST);
                }    

            }

        }

        if(!$arrayResponse){
            $response = $userResponseRepository->findBy(["User" => $user, "page" => $id]);

            // $response = ["error" => "Duplicate"];
            return $this->json($response);
        }
        
        return $this->json($arrayResponse);
    }

    #[Route('/user-response/response/{id}', name: 'response_question')]
    public function response($id, Request $request, JWTTokenManagerInterface $tokenManager, UserResponseRepository $userResponseRepository)
    {
        $user = $this->getUser();

        $data = $request->getContent();
        $data = json_decode($data, true);


        $question = $userResponseRepository->find($id);

        if(!$question){
            $response = ["error" => "Question not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }


        if(!isset($data['response'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        try{
            $question->setResponse($data["response"]);

            $userResponseRepository->add($question, true);

            return $this->json($question, Response::HTTP_OK);
        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }   
    }
}
