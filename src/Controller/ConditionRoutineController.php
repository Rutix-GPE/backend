<?php

namespace App\Controller;

use App\Entity\ConditionRoutine;
use App\Entity\UserResponse;
use App\Repository\ConditionRoutineRepository;
use App\Repository\UserResponseRepository;
use App\Repository\TemplateQuestionRepository;
use App\Service\ConditionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ConditionRoutineController extends AbstractController
{
    // NOT USED => to update
    #[Route('/condition-routine/new', name:'new_condition_routine', methods: ['POST'])]
    public function new(Request $request, ConditionService $conditionService/*, ConditionRoutineRepository $conditionRepository, TemplateQuestionRepository $TQRepository*/): JsonResponse
    {
        
        $data = $request->getContent();
        $data = json_decode($data, true);
        
        if(!isset($data['name']) || 
        !isset($data['description']) ||
        !isset($data['time']) ||
        !isset($data['question']) ||
        !isset($data['response'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
        
        // return $this->json("", 200);

        try{
            $condition = $conditionService->controllerCreateCondition($data);

            // $condition = new ConditionRoutine;

            // $condition->setName($data["name"]);
            // $condition->setDescription($data["description"]); 
            
            // $dateTime = \DateTime::createFromFormat('H:i:s', $data["time"]);
            // $condition->setTaskTime($dateTime);

            // $question = $TQRepository->find($data["question"]);
            // $condition->setQuestion($question);
            // $condition->setResponseCondition($data["response"]);

            // // return $this->json($condition, Response::HTTP_NOT_FOUND);

            // $conditionRepository->add($condition, true);

            // // return $this->json($condition, Response::HTTP_CREATED);

            return $this->json($condition, Response::HTTP_CREATED, [], [
                'groups' => 'condition_routine:read'
            ]);

        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
    }

    // NOT USED 
    #[Route('/condition-routine/question_response', name:'question_response', methods: ['GET'])]
    public function getQuestionResponse(Request $request, ConditionRoutineRepository $conditionRepository): JsonResponse
    {
        $res = $conditionRepository->findOneBy(['Question' => 34, 'responseCondition' => 'YES']);

        return $this->json($res, Response::HTTP_BAD_REQUEST);
    }
}
