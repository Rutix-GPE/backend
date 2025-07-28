<?php 

namespace App\Service;

use App\Entity\Question;
use App\Entity\Routine;
use App\Repository\RoutineRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class RoutineService extends WebTestCase
{

    private RoutineRepository $routineRepository;

    
    public function __construct(RoutineRepository $routineRepository) 
    {
        $this->routineRepository = $routineRepository;
    }

    public function show($routine)
    {
        return $this->routineRepository->findOneBy([
            'id' => $routine
        ]);
    }

    public function list()
    {
        return $this->routineRepository->findAll();
    }

    public function new($name, $description, $days, $taskTime)
    {
        $routine = new Routine;

        if(!is_string($name)||!is_string($description)||!is_array($days)||!is_object($taskTime) ){
            return -1;
        }

        $duplicateName = $this->routineRepository->findOneBy([
            'name' => $name
        ]);
        if($duplicateName) {
            return  [$duplicateName, Response::HTTP_CONFLICT];
        }

        $routine->setName($name);
        $routine->setDescription($description);
        $routine->setDays($days);
        $routine->setTaskTime($taskTime);

        $this->routineRepository->add($routine, true);

        return [$routine, Response::HTTP_CREATED];
    }

    public function edit($routineId, $data)
    {
        $routine = $this->routineRepository->findOneBy([
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

        $this->routineRepository->add($routine, true);

        return [$routine, Response::HTTP_OK];
    }

}
