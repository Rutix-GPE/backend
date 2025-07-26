<?php 

namespace App\Service;

use App\Entity\TemplateQuestion;
use App\Repository\QuestionV2Repository;
use App\Repository\RelationV2Repository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserResponseV2Repository;
use Symfony\Component\HttpFoundation\Response;

class UserResponseV2Service 
{

    private RelationV2Repository $relationV2Repository;
    private RelationV2Service $relationV2Service;
    private QuestionV2Repository $questionV2Repository;
    private QuestionV2Service $questionV2Service;
    private UserRoutineV2Service $userRoutineV2Service;
    
    public function __construct(
        RelationV2Repository $relationV2Repository, 
        RelationV2Service $relationV2Service, 
        QuestionV2Repository $questionV2Repository, 
        QuestionV2Service $questionV2Service,
        UserRoutineV2Service $userRoutineV2Service
        ) 
    {
        $this->relationV2Repository = $relationV2Repository;
        $this->relationV2Service = $relationV2Service;
        $this->questionV2Repository = $questionV2Repository;
        $this->questionV2Service = $questionV2Service;
        $this->userRoutineV2Service = $userRoutineV2Service;
    }

    public function getFirstQuestion($user)
    {
        $question = $this->questionV2Repository->findOneBy([
            'id' => 1
        ]);

        $this->questionV2Service->setNextRootQuestion($question, $user);

        $answer = $this->relationV2Service->getAnswer($question->getId());

        $array = [
            "question" => $question->getContent(), 
            "answer" => $answer,
            "url" => "user-response/v2/next-question/1"
        ];

        return $array;
    }

    public function getNextQuestion($question, $answer, $user)
    {
        $relation = $this->relationV2Service->getQuestionByIdAndAnswer($question, $answer);

        $question = $relation->getTargetQuestion();
        $routine = $relation->getTargetRoutine();

        if($question){

            $array["code"] = Response::HTTP_OK;

        } else if($routine){
            $question = $user->getNextRootQuestion();

            $routine = $this->userRoutineV2Service->copyRoutine($routine, $user);

            $array["code"] = $routine;
 
        } else {
            $question = $user->getNextRootQuestion();

            $array["code"] = Response::HTTP_OK;

        }

        $this->questionV2Service->setNextRootQuestion($question, $user);

        if($question){
            $answer = $this->relationV2Service->getAnswer($question->getId());
    
            $array["question"] = $question->getContent();
            $array["answer"] = $answer;
            $array["url"] = "user-response/v2/next-question/" . $question->getId();
    
        } else {

            $array["question"] = null;
            $array["answer"] = null;
            $array["url"] = null;
        }

        return $array;
    }


    // public function getNextQuestion($question, $answer, $user)
    // {
    //     $relation = $this->relationV2Service->getQuestionByIdAndAnswer($question, $answer);

    //     $question = $relation->getTargetQuestion();
    //     $routine = $relation->getTargetRoutine();



    //     if($question){
    //         $this->questionV2Service->setNextRootQuestion($question, $user);

    //         $answer = $this->relationV2Service->getAnswer($question->getId());
    
    //         $array = [
    //             "question" => $question->getContent(),
    //             "answer" => $answer,
    //             "url" => "user-response/v2/next-question/" . $question->getId()
    //         ];
    //     } else if($routine){
    //         $question = $user->getNextRootQuestion();
            
    //         $this->questionV2Service->setNextRootQuestion($question, $user);
            

    //         $answer = $this->relationV2Service->getAnswer($question->getId());
    
    //         $array = [
    //             "question" => $question->getContent(),
    //             "answer" => $answer,
    //             "url" => "user-response/v2/next-question/" . $question->getId()
    //         ];

    //     } else {
    //         $question = $user->getNextRootQuestion();

    //         $this->questionV2Service->setNextRootQuestion($question, $user);

    //         $answer = $this->relationV2Service->getAnswer($question->getId());
    
    //         $array = [
    //             "question" => $question->getContent(),
    //             "answer" => $answer,
    //             "url" => "user-response/v2/next-question/" . $question->getId()
    //         ];
    //     }
  

    //     return $array;


    // }

}
