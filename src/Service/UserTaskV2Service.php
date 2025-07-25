<?php 

namespace App\Service;

use App\Entity\QuestionV2;
use App\Entity\RoutineV2;
use App\Entity\UserRoutineV2;
use App\Entity\UserTaskV2;
use App\Repository\RoutineV2Repository;
use App\Repository\UserRoutineV2Repository;
use App\Repository\UserTaskV2Repository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class UserTaskV2Service extends WebTestCase
{

    private UserTaskV2Repository $userTaskV2Repository;

    
    public function __construct(UserTaskV2Repository $userTaskV2Repository) 
    {
        $this->userTaskV2Repository = $userTaskV2Repository;
    }

    public function createList(UserRoutineV2 $routine)
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

    public function createOne(UserRoutineV2 $routine, $date)
    {
        $task = new UserTaskV2;

        $task->setName($routine->getName());
        $task->setDescription($routine->getDescription());

        $task->setTaskDateTime($this->concatDateTime($routine->getTaskTime(), $date));

        $task->setStatus(false);
        $task->setUser($routine->getUser());

        $this->userTaskV2Repository->add($task, true);
    }

    public function concatDateTime($time, $date)
    {
        // Récupérer l'heure au format H:i:s
        $timeStr = $time->format('H:i:s');

        // Fusionner date + heure
        $datetimeStr = $date . ' ' . $timeStr;

        // Créer l'objet DateTime final
        $datetime = new \DateTime($datetimeStr);

        return $datetime;
    }


    // public function controllerCreateTask($data, $user)
    // {
    //     $task = new Task;

    //     $task->setName($data['name']);
    //     $task->setDescription($data['description']);


    //     $date = new DateTime($data['date']);
    //     $time = new DateTime($data['time']);
    //     $task->setTaskDate($date);
    //     $task->setTaskTime($time);

    //     $task->setUser($user);
    //     $task->setStatus("Not finish");

    //     $this->taskRepository->add($task, true);

    //     return $task;
    // }

    // public function controllerUpdateTask($taskId, $data)
    // {
    //     $task = $this->taskRepository->find($taskId);

    //     if(!$task) {
    //         return $task;
    //     }

    //     if(isset($data['name'])){

    //         $task->setName($data['name']);
    //     }

    //     if(isset($data['time'])){
    //         $time = new DateTime($data['time']);
    //         $task->setTaskTime($time);
    //     }

    //     $this->taskRepository->add($task, true);

    //     return $task;
    // }

    public function getTaskById($id)
    {
        return $this->userTaskV2Repository->findOneBy(['id' => $id]);
    }

    public function controllerUpdateTask($task, $data)
    {

        if(isset($data['name'])){
            $task->setName($data['name']);
        }

        if(isset($data['description'])){
            $task->setDescription($data['description']);
        }

        if(isset($data['dateTime'])){
            $dateTime = $data['dateTime'];
            $dateTime = new \DateTime($dateTime);

            $task->setTaskDateTime($dateTime);
        }
        
        if(isset($data['status'])){
            $task->setStatus($data['status']);
        }        

        $this->userTaskV2Repository->add($task, true);

        return $task;
    }

    public function controllerCreateTask($user, $data)
    {
        $task = new UserTaskV2;

        $task->setUser($user);

        $task->setName($data['name']);
        $task->setDescription($data['description']);
        $task->setTaskDateTime($data['dateTime']);
        $task->setStatus(false);

        $this->userTaskV2Repository->add($task, true);

        return $task;
    }

    public function controllerGetTasksByUser($user)
    {
        return $this->userTaskV2Repository->findBy(['user' => $user]);
    }

    public function controllerGetTasksByUserAndDateTime($user, $time)
    {
        return $this->userTaskV2Repository->findBy(['user' => $user->id, 'taskDateTime' => $time]);
    }

    public function controllerGetTasksByUserAndDate($user, $date): array
    {
        $tasks = $this->userTaskV2Repository->findBy(['user' => $user]);

        $filteredTasks = array_filter($tasks, function ($task) use ($date) {
            return $task->getTaskDateTime()->format('Y-m-d') === $date->format('Y-m-d');
        });

        return array_values($filteredTasks);
    }
    
}
