<?php 

namespace App\Service;

use App\Entity\RelationV2;
use App\Repository\QuestionV2Repository;
use App\Repository\RelationV2Repository;
use App\Repository\RoutineV2Repository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class RelationV2Service extends WebTestCase
{

    private RelationV2Repository $relationV2Repository;
    private RoutineV2Repository $routineV2Repository;
    private QuestionV2Repository $questionV2Repository;

    
    public function __construct(RelationV2Repository $relationV2Repository, RoutineV2Repository $routineV2Repository, QuestionV2Repository $questionV2Repository) 
    {
        $this->relationV2Repository = $relationV2Repository;
        $this->routineV2Repository = $routineV2Repository;
        $this->questionV2Repository = $questionV2Repository;
    }


    public function join($source, $target, $typeTarget, $answer)
    {
        $relation = new RelationV2;

        if($source){
            $source = $this->questionV2Repository->findOneBy([
                'name' => $source
            ]);

            $relation->setSource($source);
        }

        if($target && $typeTarget){
            if($typeTarget == "question"){
                $target = $this->questionV2Repository->findOneBy([
                    'name' => $target
                ]);

                $relation->setTargetQuestion($target);
            } else if($typeTarget == "routine"){
                $target = $this->routineV2Repository->findOneBy([
                    'name' => $target
                ]);

                $relation->setTargetRoutine($target);
            }
        }

        $relation->setAnswer($answer);

        $this->relationV2Repository->add($relation, true);

        return $relation;
    }

    public function getSource($question)
    {
        $question = $this->questionV2Repository->findOneBy([
            'id' => $question
        ]);

        $target = $this->relationV2Repository->findBy([
            'source' => $question
        ]);

        return $target;
    }

    public function getTargetQuestion($question)
    {
        $question = $this->questionV2Repository->findOneBy([
            'id' => $question
        ]);

        $target = $this->relationV2Repository->findBy([
            'targetQuestion' => $question
        ]);

        return $target;
    }

    public function getAnswer($question)
    {
        $question = $this->questionV2Repository->findOneBy([
            'id' => $question
        ]);

        $source = $this->relationV2Repository->findBy([
            'source' => $question
        ]);


        $answers = [];

        foreach ($source as $value) {
            array_push($answers, $value->getAnswer());
        }

        return $answers;
    }

    public function getQuestionByIdAndAnswer($question, $answer)
    {
        $relation =  $this->relationV2Repository->findOneBy([
            'source' => $question,
            'answer' => $answer
        ]);

        return $relation;
    }

    public function getRoutinByIdAndAnswer($question, $answer)
    {
        $relation =  $this->relationV2Repository->findOneBy([
            'source' => $question,
            'answer' => $answer
        ]);

        return $relation;
    }

    // public function getTargetRoutine($question)
    // {
    //     $question = $this->questionV2Repository->findOneBy([
    //         'id' => $question
    //     ]);

    //     $target = $this->relationV2Repository->findOneBy([
    //         'targetQuestion' => $question
    //     ]);

    //     return $target;
    // }


    // public function show($routine)
    // {
    //     return $this->routineV2Repository->findOneBy([
    //         'id' => $routine
    //     ]);
    // }

    // public function list()
    // {
    //     return $this->routineV2Repository->findAll();
    // }

    // public function new($name, $description, $days, $taskTime)
    // {
    //     $routine = new RoutineV2;

    //     if(!is_string($name)||!is_string($description)||!is_array($days)||!is_object($taskTime) ){
    //         return -1;
    //     }

    //     $duplicateName = $this->routineV2Repository->findOneBy([
    //         'name' => $name
    //     ]);
    //     if($duplicateName) {
    //         return  [$duplicateName, Response::HTTP_CONFLICT];
    //     }

    //     $routine->setName($name);
    //     $routine->setDescription($description);
    //     $routine->setDays($days);
    //     $routine->setTaskTime($taskTime);

    //     $this->routineV2Repository->add($routine, true);

    //     return [$routine, Response::HTTP_CREATED];
    // }

    // public function edit($routineId, $data)
    // {
    //     $routine = $this->routineV2Repository->findOneBy([
    //         'id' => $routineId
    //     ]);

    //     if(isset($data['description'])){
    //         $routine->setDescription($data['description']);
    //     }
    //     if(isset($data['days'])){
    //         $routine->setDays($data['days']);
    //     }
    //     if(isset($data['taskTime'])){
    //         $routine->setTaskTime($data['taskTime']);
    //     }

    //     $this->routineV2Repository->add($routine, true);

    //     return [$routine, Response::HTTP_OK];
    // }

}
