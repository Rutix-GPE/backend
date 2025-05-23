<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class TaskController extends AbstractController
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
        TaskRepository $taskRepository
    ): JsonResponse { 
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        try {

            $task = new Task;

            $task->setName($data['name']);
            $task->setDescription($data['description']);


            $date = new DateTime($data['taskDate']);
            $time = new DateTime($data['taskTime']);
            $task->setTaskDate($date);
            $task->setTaskTime($time);

            $task->setUser($user);
            $task->setStatus("Not finish");

            $taskRepository->add($task, true);

            return $this->json($task, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/task/update/{task}', name: 'update_task', methods: ['PATCH'])]
    public function updateTask(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        TaskRepository $taskRepository,
        $task
    ): JsonResponse { 
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        try {

            $task = $taskRepository->find($task);

            if(!$task) {
                return $this->json($task, Response::HTTP_NOT_FOUND);
            }

            if(isset($data['name'])){

                $task->setName($data['name']);
            }

            if(isset($data['taskTime'])){
                $time = new DateTime($data['taskTime']);
                $task->setTaskTime($time);
            }

            $taskRepository->add($task, true);

            return $this->json($task, Response::HTTP_OK);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/task/get-by-user', name: 'task_by_user', methods: ['GET'])]
    public function getTasksByUser(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        TaskRepository $taskRepository
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        try {

            $tasks = $taskRepository->findBy(['User' => $user->id]);

            return $this->json($tasks, Response::HTTP_OK);

            // return $this->json($userResponse, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/task/get-by-user-and-time/{time}', name: 'task_by_user_and_time', methods: ['GET'])]
    public function getTasksByUserAndTime(
        Request $request,
        JWTTokenManagerInterface $tokenManager,
        TaskRepository $taskRepository,
        $time
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $time = new DateTime($time);

        try {

            $tasks = $taskRepository->findBy(['User' => $user->id, 'taskDate' => $time]);

            return $this->json($tasks, Response::HTTP_OK);

            // return $this->json($userResponse, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
