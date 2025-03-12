<?php 

namespace App\Service;

use App\Entity\ConditionRoutine;
use App\Entity\Routine;
use App\Entity\User;
use App\Repository\ConditionRoutineRepository;
use App\Repository\RoutineRepository;
use App\Repository\TaskRepository;

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

        // TODO check si cette routine existe déjà pour cette user 
        $this->routineRepository->add($routine, true);

        // TODO mettre ici un système d'état de création des tâches pour refresh le front

        $this->taskService->createList($routine);
    }


    public function controllerGetRoutineByUser($userId)
    {
        return $this->routineRepository->findBy(['User' => $userId]);
    }

}
