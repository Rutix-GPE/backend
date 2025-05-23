<?php 

namespace App\Service;

use App\Entity\QuestionV2;
use App\Entity\RoutineV2;
use App\Repository\RoutineV2Repository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class RoutineV2Service extends WebTestCase
{

    private RoutineV2Repository $routineV2Repository;

    
    public function __construct(RoutineV2Repository $routineV2Repository) 
    {
        $this->routineV2Repository = $routineV2Repository;
    }

    public function show($routine)
    {
        return $this->routineV2Repository->findOneBy([
            'id' => $routine
        ]);
    }

    public function list()
    {
        return $this->routineV2Repository->findAll();
    }

    public function new($name, $description, $days, $taskTime)
    {
        $routine = new RoutineV2;

        if(!is_string($name)||!is_string($description)||!is_array($days)||!is_object($taskTime) ){
            return -1;
        }

        $duplicateName = $this->routineV2Repository->findOneBy([
            'name' => $name
        ]);
        if($duplicateName) {
            return  [$duplicateName, Response::HTTP_CONFLICT];
        }

        $routine->setName($name);
        $routine->setDescription($description);
        $routine->setDays($days);
        $routine->setTaskTime($taskTime);

        $this->routineV2Repository->add($routine, true);

        return [$routine, Response::HTTP_CREATED];
    }

    public function edit($routineId, $data)
    {
        $routine = $this->routineV2Repository->findOneBy([
            'id' => $routineId
        ]);

        if(isset($data['description'])){
            $routine->setDescription($data['description']);
        }
        if(isset($data['days'])){
            $routine->setDays($data['days']);
        }
        if(isset($data['taskTime'])){
            $routine->setTaskTime($data['taskTime']);
        }

        $this->routineV2Repository->add($routine, true);

        return [$routine, Response::HTTP_OK];
    }

}
