<?php 

namespace App\Service;

use App\Entity\TemplateQuestion;
use App\Repository\QuestionRepository;
use App\Repository\RelationRepository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserResponseRepository;
use Symfony\Component\HttpFoundation\Response;

class UserResponseService 
{

    private RelationRepository $relationRepository;
    private RelationService $relationService;
    private QuestionRepository $questionRepository;
    private QuestionService $questionService;
    private UserRoutineService $userRoutineService;
    
    public function __construct(
        RelationRepository $relationRepository, 
        RelationService $relationService, 
        QuestionRepository $questionRepository, 
        QuestionService $questionService,
        UserRoutineService $userRoutineService
        ) 
    {
        $this->relationRepository = $relationRepository;
        $this->relationService = $relationService;
        $this->questionRepository = $questionRepository;
        $this->questionService = $questionService;
        $this->userRoutineService = $userRoutineService;
    }

    public function getFirstQuestion($user)
    {
        $question = $this->questionRepository->findOneBy([
            'id' => 1
        ]);

        $this->questionService->setNextRootQuestion($question, $user);

        $answer = $this->relationService->getAnswer($question->getId());

        $array = [
            "question" => $question->getContent(), 
            "answer" => $answer,
            "url" => "user-response/next-question/1"
        ];

        return $array;
    }

    public function getNextQuestion($question, $answer, $user)
    {
        $relation = $this->relationService->getQuestionByIdAndAnswer($question, $answer);

        $question = $relation->getTargetQuestion();
        $routine = $relation->getTargetRoutine();

        if($question){

            $array["code"] = Response::HTTP_OK;

        } else if($routine){
            $question = $user->getNextRootQuestion();

            $routine = $this->userRoutineService->copyRoutine($routine, $user);

            $array["code"] = $routine;
 
        } else {
            $question = $user->getNextRootQuestion();

            $array["code"] = Response::HTTP_OK;

        }

        $this->questionService->setNextRootQuestion($question, $user);

        if($question){
            $answer = $this->relationService->getAnswer($question->getId());
    
            $array["question"] = $question->getContent();
            $array["answer"] = $answer;
            $array["url"] = "user-response/next-question/" . $question->getId();
    
        } else {

            $array["question"] = null;
            $array["answer"] = null;
            $array["url"] = null;
        }

        return $array;
    }

}
