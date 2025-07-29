<?php 

namespace App\Service;

use App\Entity\Question;
use App\Entity\Routine;
use App\Entity\UserRoutine;
use App\Repository\RoutineRepository;
use App\Repository\UserRoutineRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class UserRoutineService 
{

    private UserRoutineRepository $userRoutineRepository;
    private UserTaskService $userTaskService;

    
    public function __construct(UserRoutineRepository $userRoutineRepository, UserTaskService $userTaskService) 
    {
        $this->userRoutineRepository = $userRoutineRepository;
        $this->userTaskService = $userTaskService;
    }

    public function copyRoutine(Routine $routine, $user)
    {
        $userRoutine = new UserRoutine;
        $userRoutine->copyRoutine($routine, $user);

        $duplicate = $this->userRoutineRepository->findOneBy([
            'user' => $user,
            'name' => $routine->getName()
        ]);

        if($duplicate){
            return Response::HTTP_CONFLICT;
        }

        $this->userRoutineRepository->add($userRoutine, true);

        $this->userTaskService->createList($userRoutine);

        $userRoutine->setIsAllTaskGenerated(true);
        $this->userRoutineRepository->add($userRoutine, true);

        return Response::HTTP_CREATED;
    }

    public function controllerGetRoutineByUser($userId)
    {
        return $this->userRoutineRepository->findBy(['user' => $userId]);
    }

}
