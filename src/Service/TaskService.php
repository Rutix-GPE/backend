<?php

namespace App\Service;

use App\Dto\Task\TaskInputUpdateDTO;
use DateTime;
use App\Entity\Task;
use App\Entity\Routine;
use App\Dto\Task\TaskInputDTO;
use App\Dto\Task\TaskOutputDTO;
use App\Repository\TaskRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class TaskService 
{
    
    public function __construct(
        private TaskRepository $taskRepository,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) 
    {}

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

    public function controllerCreateTask($request, $user)
    {

        $dto = $this->serializer->deserialize($request->getContent(), TaskInputDTO::class, 'json');
        $errors = $this->validator->validate($dto); 
        if (count($errors) > 0) {
            throw new BadRequestHttpException($errors);
        }
        $task = new Task;
        $task->setName($dto->name);
        $task->setDescription($dto->description);
        $task->setTaskDate($dto->taskDate);
        $task->setTaskTime($dto->taskTime);
        $task->setUser($user);
        $task->setStatus("Not finish");
        $this->taskRepository->add($task, true);
        $taskDto = new TaskOutputDTO($task);
        return $taskDto;
    }

    public function controllerUpdateTask($taskId, $request): TaskOutputDTO
    {
        $inputDto = $this->serializer->deserialize($request->getContent(), TaskInputUpdateDTO::class, 'json');
        $errors = $this->validator->validate($inputDto);
        if (count($errors) > 0) {
            dd($errors);
            throw new BadRequestHttpException("Données invalides. ");
        }
        return (new TaskOutputDTO($this->updateTask($inputDto, $taskId)));
    }

    public function updateTask($inputDto, $taskId){
        $task = $this->taskRepository->find($taskId);
        if(!$task) {
            throw new NotFoundHttpException("Not Found");
        }
        if ($inputDto->name)        $task->setName($inputDto->name);
        if($inputDto->description)  $task->setDescription($inputDto->description);
        if($inputDto->taskDate) {
            $date = \DateTime::createFromFormat('Y-m-d', $inputDto->taskDate);
            if (!$date) throw new BadRequestHttpException("Format de date invalide : attendu 'Y-m-d'");
            $task->setTaskDate($date);
        }
        
        if($inputDto->taskTime) {
            $time = \DateTime::createFromFormat('H:i:s', $inputDto->taskTime);
            if (!$time) throw new BadRequestHttpException("Format d'heure invalide : attendu 'H:i:s'");
            $task->setTaskTime($time);
        }
        if($inputDto->status)       $task->setStatus($inputDto->status);
        $this->taskRepository->add($task, true);
        return $task;
    }
    public function controllerGetTasksByUser($user)
    {
        $tasks = $this->taskRepository->findBy(['User' => $user]);
        return array_map(fn($task) => new TaskOutputDTO($task), $tasks);    }

    public function controllerGetTasksByUserAndTime($user, $time)
    {
        $tasks = $this->taskRepository->findBy(['User' => $user->id, 'taskDate' => $time]);
        return array_map(fn($task) => new TaskOutputDTO($task), $tasks);  
    }
} 