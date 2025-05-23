<?php 

namespace App\Service;

use App\Entity\QuestionV2;
use App\Repository\QuestionV2Repository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class QuestionV2Service extends WebTestCase
{

    private QuestionV2Repository $questionV2Repository;

    
    public function __construct(QuestionV2Repository $questionV2Repository) 
    {
        $this->questionV2Repository = $questionV2Repository;
    }

    public function show($question)
    {
        return $this->questionV2Repository->findOneBy([
            'id' => $question
        ]);
    }

    public function list()
    {
        return $this->questionV2Repository->findAll();
    }

    public function new($name, $content, $rootQuestion, $quickQuestion)
    {
        $questionv2 = new QuestionV2;

        if(!is_string($name)||!is_string($content)||!is_bool($rootQuestion)||!is_bool($quickQuestion) ){
            return -1;
        }

        $duplicateName = $this->questionV2Repository->findOneBy([
            'name' => $name
        ]);
        if($duplicateName) {
            return  [$duplicateName, Response::HTTP_CONFLICT];
        }

        $questionv2->setName($name);
        $questionv2->setContent($content);
        $questionv2->setIsRootQuestion($rootQuestion);
        $questionv2->setIsQuickQuestion($quickQuestion);

        $this->questionV2Repository->add($questionv2, true);

        return [$questionv2, Response::HTTP_CREATED];
    }

    public function edit($questionId, $data)
    {
        $question = $this->questionV2Repository->findOneBy([
            'id' => $questionId
        ]);

        if(isset($data['content'])){
            $question->setContent($data['content']);
        }
        if(isset($data['rootQuestion'])){
            $question->setIsRootQuestion($data['rootQuestion']);
        }
        if(isset($data['rootQuestion'])){
            $question->setIsQuickQuestion($data['rootQuestion']);
        }

        $this->questionV2Repository->add($question, true);

        return [$question, Response::HTTP_OK];
    }


}
