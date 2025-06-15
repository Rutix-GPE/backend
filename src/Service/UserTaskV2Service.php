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
        // $task->setTaskTime($routine->getTaskTime());
        // if (is_string($date)) {
        //     $date = DateTime::createFromFormat('Y-m-d', $date); // Adapter le format à ton besoin (par exemple 'Y-m-d')
        // }
        // $task->setTaskDate($date);

        // $task->setTaskDateTime();

        $task->setTaskDateTime($this->concatDateTime($routine->getTaskTime(), $date));

        $task->setStatus(false);
        $task->setUser($routine->getUser());


        // $this->entityManager->persist($task);
        // $this->entityManager->flush();

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

}
