<?php 

namespace App\Service;

use App\Dto\Routine\RoutineOutputDTO;
use App\Entity\ConditionRoutine;
use App\Entity\Routine;
use App\Repository\RoutineRepository;

class RoutineService 
{

    private RoutineRepository $routineRepository;
    private TaskService $taskService;

    
    public function __construct(RoutineRepository $routineRepository, TaskService $taskService) 
    {
        $this->routineRepository = $routineRepository;
        $this->taskService = $taskService;
    }

    public function createRoutine(ConditionRoutine $condition, $user)
    {
        $routine = new Routine;

        $routine->setName($condition->getName());
        $routine->setDescription($condition->getDescription());
        $routine->setTaskTime($condition->getTaskTime());
        $routine->setDays($condition->getDays());
        $routine->setUser($user);
        $this->routineRepository->add($routine, true);
        $this->taskService->createList($routine);
    }


    public function controllerGetRoutineByUser($userId)
    {
        $routines =  $this->routineRepository->findBy(['User' => $userId]);
       return( array_map(fn($routine) => new RoutineOutputDTO($routine), $routines));
    }

}
