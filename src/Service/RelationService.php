<?php 

namespace App\Service;

use App\Entity\Relation;
use App\Repository\QuestionRepository;
use App\Repository\RelationRepository;
use App\Repository\RoutineRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class RelationService extends WebTestCase
{

    private RelationRepository $relationRepository;
    private RoutineRepository $routineRepository;
    private QuestionRepository $questionRepository;

    
    public function __construct(RelationRepository $relationRepository, RoutineRepository $routineRepository, QuestionRepository $questionRepository) 
    {
        $this->relationRepository = $relationRepository;
        $this->routineRepository = $routineRepository;
        $this->questionRepository = $questionRepository;
    }


    public function join($source, $target, $typeTarget, $answer)
    {
        $relation = new Relation;

        if($source){
            $source = $this->questionRepository->findOneBy([
                'name' => $source
            ]);

            $relation->setSource($source);
        }

        if($target && $typeTarget){
            if($typeTarget == "question"){
                $target = $this->questionRepository->findOneBy([
                    'name' => $target
                ]);

                $relation->setTargetQuestion($target);
            } else if($typeTarget == "routine"){
                $target = $this->routineRepository->findOneBy([
                    'name' => $target
                ]);

                $relation->setTargetRoutine($target);
            }
        }

        $relation->setAnswer($answer);

        $this->relationRepository->add($relation, true);

        return $relation;
    }

    public function getSource($question)
    {
        $question = $this->questionRepository->findOneBy([
            'id' => $question
        ]);

        $target = $this->relationRepository->findBy([
            'source' => $question
        ]);

        return $target;
    }

    public function getTargetQuestion($question)
    {
        $question = $this->questionRepository->findOneBy([
            'id' => $question
        ]);

        $target = $this->relationRepository->findBy([
            'targetQuestion' => $question
        ]);

        return $target;
    }

    public function getAnswer($question)
    {
        $question = $this->questionRepository->findOneBy([
            'id' => $question
        ]);

        $source = $this->relationRepository->findBy([
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
        $relation =  $this->relationRepository->findOneBy([
            'source' => $question,
            'answer' => $answer
        ]);

        return $relation;
    }

    public function getRoutinByIdAndAnswer($question, $answer)
    {
        $relation =  $this->relationRepository->findOneBy([
            'source' => $question,
            'answer' => $answer
        ]);

        return $relation;
    }

}
