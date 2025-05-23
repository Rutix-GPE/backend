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
    public function __construct(
        private readonly ConditionService $conditionService,
    ) {}
    // NOT USED => to update
    #[Route('/condition-routine/new', name:'new_condition_routine', methods: ['POST'])]
    public function newCondtionRoutine(Request $request): JsonResponse
    {   
        $condtionRoutineDto = $this->conditionService->controllerCreateCondition($request);
        return $this->json($condtionRoutineDto, Response::HTTP_CREATED, [], [
            'groups' => 'condition_routine:read'
        ]);
    }

    // NOT USED 
    #[Route('/condition-routine/question_response', name:'question_response', methods: ['GET'])]
    public function getQuestionResponse(Request $request, ConditionRoutineRepository $conditionRepository): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        if(!isset($data['question']) ||
        !isset($data['response'])){
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        $res = $conditionRepository->findOneBy(['Question' => $data['question'], 'responseCondition' => $data['response']]);

        if($res){
            return $this->json($res, Response::HTTP_OK);
        }

        return $this->json($res, Response::HTTP_NO_CONTENT);
    }
}
