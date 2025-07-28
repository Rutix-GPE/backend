<?php 

namespace App\Service;

use App\Entity\Question;
use App\Entity\Routine;
use App\Entity\UserRoutine;
use App\Entity\UserTask;
use App\Repository\RoutineRepository;
use App\Repository\UserRoutineRepository;
use App\Repository\UserTaskRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserTaskService extends WebTestCase
{
    private UserTaskRepository $userTaskRepository;
    public function __construct(UserTaskRepository $userTaskRepository) 
    {
        $this->userTaskRepository = $userTaskRepository;
    }

    public function getTaskById($id)
    {
        return $this->userTaskRepository->findOneBy(['id' => $id]);
    }

    public function createList(UserRoutine $routine)
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

    public function createOne(UserRoutine $routine, $date)
    {
        $task = new UserTask;

        $task->setName($routine->getName());
        $task->setDescription($routine->getDescription());

        $task->setTaskDateTime($this->concatDateTime($routine->getTaskTime(), $date));

        $task->setStatus(false);
        $task->setUser($routine->getUser());

        $this->userTaskRepository->add($task, true);
    }

    public function concatDateTime($time, $date)
    {
        $timeStr = $time->format('H:i:s');

        $datetimeStr = $date . ' ' . $timeStr;

        $datetime = new \DateTime($datetimeStr);

        return $datetime;
    }

    public function controllerUpdateTask($task, $data)
    {

        if(isset($data['name'])){
            $task->setName($data['name']);
        }

        if(isset($data['description'])){
            $task->setDescription($data['description']);
        }

        if(isset($data['taskDateTime'])){
            $dateTime = $data['taskDateTime'];
            $dateTime = new \DateTime($dateTime);

            $task->setTaskDateTime($dateTime);
        }
        
        if(isset($data['status'])){
            $task->setStatus($data['status']);
        }        

        $this->userTaskRepository->add($task, true);

        return $task;
    }

    public function controllerCreateTask($user, $data)
    {
        $task = new UserTask;

        $task->setUser($user);

        $task->setName($data['name']);
        $task->setDescription($data['description']);
        $task->setTaskDateTime($data['taskDateTime']);
        $task->setStatus(false);

        $this->userTaskRepository->add($task, true);

        return $task;
    }

    public function controllerGetTasksByUser($user)
    {
        return $this->userTaskRepository->findBy(['user' => $user]);
    }

    public function controllerGetTasksByUserAndDateTime($user, $time)
    {
        return $this->userTaskRepository->findBy(['user' => $user->id, 'taskDateTime' => $time]);
    }

    public function controllerGetTasksByUserAndDate($user, $date): array
    {
        $tasks = $this->userTaskRepository->findBy(['user' => $user]);

        $filteredTasks = array_filter($tasks, function ($task) use ($date) {
            return $task->getTaskDateTime()->format('Y-m-d') === $date->format('Y-m-d');
        });

        return array_values($filteredTasks);
    }

    public function controllerDeleteTask($taskId): void
    {
        $task = $this->getTaskById($taskId);
        if(!$task){
            throw new BadRequestHttpException("Task not found");
        }
        $this->userTaskRepository->remove($task, true);
    }
    
}
