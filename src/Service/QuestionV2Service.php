<?php 

namespace App\Service;

use App\Entity\QuestionV2;
use App\Repository\QuestionV2Repository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\isNull;

class QuestionV2Service extends WebTestCase
{

    private QuestionV2Repository $questionV2Repository;
    private UserRepository $userRepository;

    
    public function __construct(QuestionV2Repository $questionV2Repository, UserRepository $userRepository) 
    {
        $this->questionV2Repository = $questionV2Repository;
        $this->userRepository = $userRepository;
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

    public function listRoot()
    {
        return $this->questionV2Repository->findBy([
            'isRootQuestion' => true
        ]);
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

    function setNextRootQuestion($question, $user)
    {
        if($question)
        {
            if($question->isRootQuestion()){
                $allQuestions = $this->questionV2Repository->findBy([
                    'isRootQuestion' => true
                ]);
                
                foreach($allQuestions as $index => $oneQuestion) {
                    if($oneQuestion->getId() == $question->getId()){
                        $next = $allQuestions[$index + 1] ?? null;
                    }
                }
                
                $user->setNextRootQuestion($next);
    
                $this->userRepository->add($user, true);
            }
        }
    }

}
