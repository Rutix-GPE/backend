<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use App\Service\UserTaskV2Service;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserTaskV2Controller extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/task/create', name: 'create_task', methods: ['POST'])]
    public function createTask(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        // TaskRepository $taskRepository,
        TaskService $taskService
    ): JsonResponse { 
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        if(!isset($data['name']) || 
        !isset($data['description']) ||
        !isset($data['date']) ||
        !isset($data['time'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $task = $taskService->controllerCreateTask($data, $user);

            // $task = new Task;

            // $task->setName($data['name']);
            // $task->setDescription($data['description']);


            // $date = new DateTime($data['date']);
            // $time = new DateTime($data['time']);
            // $task->setTaskDate($date);
            // $task->setTaskTime($time);

            // $task->setUser($user);
            // $task->setStatus("Not finish");

            // $taskRepository->add($task, true);

            return $this->json($task, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/task/update/{task}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        // TaskRepository $taskRepository,
        TaskService $taskService,
        $task
    ): JsonResponse { 
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        try {
            $task = $taskService->controllerUpdateTask($task, $data);

            // $task = $taskRepository->find($task);

            // if(!$task) {
            //     return $this->json($task, Response::HTTP_NOT_FOUND);
            // }

            // if(isset($data['name'])){

            //     $task->setName($data['name']);
            // }

            // if(isset($data['time'])){
            //     $time = new DateTime($data['time']);
            //     $task->setTaskTime($time);
            // }

            // $taskRepository->add($task, true);


            if(!$task) {
                return $this->json($task, Response::HTTP_NOT_FOUND);
            }


            return $this->json($task, Response::HTTP_OK);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage() . " à la ligne " . $error->getLine() . " dans " . $error->getFile()], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/user-task/v2/get-by-user', name: 'task_by_user', methods: ['GET'])]
    public function getTasksByUser(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        // TaskRepository $taskRepository
        UserTaskV2Service $userTaskV2Service
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $tasks = $userTaskV2Service->controllerGetTasksByUser($user->id);

            // $tasks = $taskRepository->findBy(['User' => $user->id]);

            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'usertask:read']);

            // return $this->json($userResponse, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/user-task/v2/get-by-user-and-datetime/{dateTime}', name: 'task_by_user_and_time', methods: ['GET'])]
    public function getTasksByUserAndTime(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        // TaskRepository $taskRepository,
        UserTaskV2Service $userTaskV2Service,
        string $dateTime
    ): JsonResponse {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $formattedDateTime = str_replace('_', ' ', $dateTime);
            $dateTime = new \DateTime($formattedDateTime);

            $tasks = $userTaskV2Service->controllerGetTasksByUserAndTime($user, $dateTime);
            // $tasks = $taskRepository->findBy(['User' => $user->id, 'taskDate' => $time]);

            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'usertask:read']);

            // return $this->json($userResponse, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
