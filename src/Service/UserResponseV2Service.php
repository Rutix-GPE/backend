<?php 

namespace App\Service;

use App\Entity\TemplateQuestion;
use App\Repository\QuestionV2Repository;
use App\Repository\RelationV2Repository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserResponseV2Repository;

class UserResponseV2Service 
{

    private RelationV2Repository $relationV2Repository;
    private RelationV2Service $relationV2Service;
    private QuestionV2Repository $questionV2Repository;
    
    public function __construct(RelationV2Repository $relationV2Repository, RelationV2Service $relationV2Service, QuestionV2Repository $questionV2Repository) 
    {
        $this->relationV2Repository = $relationV2Repository;
        $this->relationV2Service = $relationV2Service;
        $this->questionV2Repository = $questionV2Repository;
    }

    public function getFirstQuestion()
    {
        // $relationService = new RelationV2Service;

        $question = $this->questionV2Repository->findOneBy([
            'id' => 1
        ]);

        $answer = $this->relationV2Service->getAnswer($question->getId());

        $array = [
            "question" => $question->getContent(), 
            "answer" => $answer,
            "url" => "user-response/v2/next-question/1"
        ];

        return $array;
    }

}
