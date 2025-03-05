<?php 

namespace App\Service;

use App\Entity\Routine;
use App\Entity\User;
use App\Repository\ConditionRoutineRepository;
use App\Repository\RoutineRepository;
use App\Repository\TaskRepository;

class ConditionService 
{

    private ConditionRoutineRepository $conditionRepository;

    private RoutineService $routineService;

    
    public function __construct(
        ConditionRoutineRepository $conditionRepository, 
        RoutineService $routineService
        ) 
    {
        $this->conditionRepository = $conditionRepository;
        $this->routineService = $routineService;
    }

    public function createRoutineByCondition($question, $response, User $user)
    {
        $condition = $this->conditionRepository->findOneBy(['Question' => $question, 'responseCondition' => $response]);

        if($condition) {

            $this->routineService->createRoutine($condition, $user);

        }
    }

}
