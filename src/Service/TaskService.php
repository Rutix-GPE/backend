<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\Routine;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class TaskService 
{
    private TaskRepository $taskRepository;

    
    public function __construct(TaskRepository $taskRepository) 
    {
        $this->taskRepository = $taskRepository;
    }

    public function createList(Routine $routine)
    {
        $today = new DateTime();

        $nextWeek = new DateTime('+1 week');

        while ($today <= $nextWeek) {
            $todayF = $today->format('N');

            if( in_array($todayF, $routine->getDays()) ) {

                $this->createOne($routine, $today->format('Y-m-d'));
            } 

            $today->modify('+1 day'); 
        }
    }

    public function createOne(Routine $routine, $date)
    {
        $task = new Task;

        $task->setName($routine->getName());
        $task->setDescription($routine->getDescription());
        $task->setTaskTime($routine->getTaskTime());
        if (is_string($date)) {
            $date = DateTime::createFromFormat('Y-m-d', $date); // Adapter le format à ton besoin (par exemple 'Y-m-d')
        }
        $task->setTaskDate($date);
        $task->setStatus("Not finish");
        $task->setUser($routine->getUser());


        // $this->entityManager->persist($task);
        // $this->entityManager->flush();

        $this->taskRepository->add($task, true);
    }

    public function controllerCreateTask($data, $user)
    {
        $task = new Task;

        $task->setName($data['name']);
        $task->setDescription($data['description']);


        $date = new DateTime($data['date']);
        $time = new DateTime($data['time']);
        $task->setTaskDate($date);
        $task->setTaskTime($time);

        $task->setUser($user);
        $task->setStatus("Not finish");

        $this->taskRepository->add($task, true);

        return $task;
    }

    public function controllerUpdateTask($taskId, $data)
    {
        $task = $this->taskRepository->find($taskId);

        if(!$task) {
            return $task;
        }

        if(isset($data['name'])){

            $task->setName($data['name']);
        }

        if(isset($data['time'])){
            $time = new DateTime($data['time']);
            $task->setTaskTime($time);
        }

        $this->taskRepository->add($task, true);

        return $task;
    }

    public function controllerGetTasksByUser($user)
    {
        return $this->taskRepository->findBy(['User' => $user]);
    }

    public function controllerGetTasksByUserAndTime($user, $time)
    {
        return $this->taskRepository->findBy(['User' => $user->id, 'taskDate' => $time]);
    }
} 