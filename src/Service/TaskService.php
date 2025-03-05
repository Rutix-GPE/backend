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
} 