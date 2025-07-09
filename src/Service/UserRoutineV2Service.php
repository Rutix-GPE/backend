<?php 

namespace App\Service;

use App\Entity\QuestionV2;
use App\Entity\RoutineV2;
use App\Entity\UserRoutineV2;
use App\Repository\RoutineV2Repository;
use App\Repository\UserRoutineV2Repository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class UserRoutineV2Service extends WebTestCase
{

    private UserRoutineV2Repository $userRoutineV2Repository;
    private UserTaskV2Service $userTaskV2Service;

    
    public function __construct(UserRoutineV2Repository $userRoutineV2Repository, UserTaskV2Service $userTaskV2Service) 
    {
        $this->userRoutineV2Repository = $userRoutineV2Repository;
        $this->userTaskV2Service = $userTaskV2Service;
    }

    public function copyRoutine(RoutineV2 $routine, $user)
    {
        $userRoutine = new UserRoutineV2;
        $userRoutine->copyRoutine($routine, $user);

        $duplicate = $this->userRoutineV2Repository->findOneBy([
            'user' => $user,
            'name' => $routine->getName()
        ]);

        if($duplicate){
            return Response::HTTP_CONFLICT;
        }

        $this->userRoutineV2Repository->add($userRoutine, true);

        $this->userTaskV2Service->createList($userRoutine);

        $userRoutine->setIsAllTaskGenerated(true);
        $this->userRoutineV2Repository->add($userRoutine, true);

        return Response::HTTP_CREATED;
    }

    public function controllerGetRoutineByUser($userId)
    {
        return $this->userRoutineV2Repository->findBy(['user' => $userId]);
    }

}
