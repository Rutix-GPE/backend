<?php
namespace App\Controller;

use DateTime;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends AbstractController
{

    public function __construct(
        private readonly TaskService $taskService,
    ) {}
    //je sais pas a quoi tu sert
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    //Good
    #[Route('/task/create', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request,): JsonResponse { 
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $taskDto = $this->taskService->controllerCreateTask($request, $user);
        return $this->json($taskDto, Response::HTTP_CREATED);

    }
//Good
    #[Route('/task/update/{task}', name: 'update_task', methods: ['PATCH'])]
    public function updateTask(Request $request,$task): JsonResponse { 
      
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }
        $taskDto= $this->taskService->controllerUpdateTask($task, $request);
        return $this->json($taskDto, Response::HTTP_OK);
    }
//faite
    #[Route('/task/get-by-user', name: 'task_by_user', methods: ['GET'])]
    public function getTasksByUser(
        Request $request,
  
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $listTaskDto = $this->taskService->controllerGetTasksByUser($user->getId());
        return $this->json($listTaskDto, Response::HTTP_OK);

    }
// pas encore fait
    #[Route('/task/get-by-user-and-time/{time}', name: 'task_by_user_and_time', methods: ['GET'])]
    public function getTasksByUserAndTime(
        $time
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException("non autorisé");
        }
        $time = new DateTime($time);
        $listTaskDto = $this->taskService->controllerGetTasksByUserAndTime($user, $time);
        return $this->json($listTaskDto, Response::HTTP_OK);
    }
}
