<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use App\Service\UserTaskService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserTaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    #[Route('/user-task/delete/{task}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(
        $task,
        UserTaskService $userTaskService
    ): JsonResponse {
            $userTaskService->controllerDeleteTask($task);
            return $this->json("success", Response::HTTP_OK);
    } 

    #[Route('/user-task/create', name: 'create_task', methods: ['POST'])]
    public function createTask(
        Request $request,
        UserTaskService $userTaskService
    ): JsonResponse { 
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }
        $data = $request->getContent();
        $data = json_decode($data, true);

        if(!isset($data['name']) || 
        !isset($data['description']) ||
        !isset($data['taskDateTime'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }


        try {

            $dateTime = $data['taskDateTime'];
            $dateTime = new \DateTime($dateTime);
            $data['taskDateTime'] = $dateTime;
            $task = $userTaskService->controllerCreateTask($user, $data);
            return $this->json($task, Response::HTTP_CREATED, [], ['groups' => 'usertask:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/user-task/update/{task}', name: 'update_task', methods: ['PATCH'])]
    public function updateTask(
        Request $request,
        UserTaskService $userTaskService,
        $task
    ): JsonResponse { 
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $task = $userTaskService->getTaskById($task);
        if($task->getUser()->id != $user->id){
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        } 


        $data = $request->getContent();
        $data = json_decode($data, true);

        try {
            $task = $userTaskService->controllerUpdateTask($task, $data);
            if(!$task) {
                return $this->json($task, Response::HTTP_NOT_FOUND);
            }


            return $this->json($task, Response::HTTP_OK, [], ['groups' => 'usertask:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage() . " à la ligne " . $error->getLine() . " dans " . $error->getFile()], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/user-task/get-by-user', name: 'task_by_user', methods: ['GET'])]
    public function getTasksByUser(
        Request $request,
        UserTaskService $userTaskService
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $tasks = $userTaskService->controllerGetTasksByUser($user->id);

            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'usertask:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/user-task/get-by-user-and-datetime/{dateTime}', name: 'task_by_user_and_time', methods: ['GET'])]
    public function getTasksByUserAndDateTime(
        Request $request,
        UserTaskService $userTaskService,
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

            $tasks = $userTaskService->controllerGetTasksByUserAndDateTime($user, $dateTime);

            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'usertask:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/user-task/get-by-user-and-date/{date}', name: 'task_by_user_and_date', methods: ['GET'])]
    public function getTasksByUserAndDate(
        Request $request,
        UserTaskService $userTaskService,
        string $date
    ): JsonResponse {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $dateObject = \DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateObject) {
                return $this->json(['error' => 'Invalid date format, expected Y-m-d'], Response::HTTP_BAD_REQUEST);
            }

            $tasks = $userTaskService->controllerGetTasksByUserAndDate($user, $dateObject);

            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'usertask:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
