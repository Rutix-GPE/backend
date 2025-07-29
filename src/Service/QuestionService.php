<?php 

namespace App\Service;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\isNull;

class QuestionService
{

    private QuestionRepository $questionRepository;
    private UserRepository $userRepository;

    
    public function __construct(QuestionRepository $questionRepository, UserRepository $userRepository) 
    {
        $this->questionRepository = $questionRepository;
        $this->userRepository = $userRepository;
    }

    public function show($question)
    {
        return $this->questionRepository->findOneBy([
            'id' => $question
        ]);
    }

    public function list()
    {
        return $this->questionRepository->findAll();
    }

    public function listRoot()
    {
        return $this->questionRepository->findBy([
            'isRootQuestion' => true
        ]);
    }

    public function new($name, $content, $rootQuestion, $quickQuestion)
    {
        $question = new Question;

        if(!is_string($name)||!is_string($content)||!is_bool($rootQuestion)||!is_bool($quickQuestion) ){
            return -1;
        }

        $duplicateName = $this->questionRepository->findOneBy([
            'name' => $name
        ]);
        if($duplicateName) {
            return  [$duplicateName, Response::HTTP_CONFLICT];
        }

        $question->setName($name);
        $question->setContent($content);
        $question->setIsRootQuestion($rootQuestion);
        $question->setIsQuickQuestion($quickQuestion);

        $this->questionRepository->add($question, true);

        return [$question, Response::HTTP_CREATED];
    }

    public function edit($questionId, $data)
    {
        $question = $this->questionRepository->findOneBy([
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

        $this->questionRepository->add($question, true);

        return [$question, Response::HTTP_OK];
    }

    function setNextRootQuestion($question, $user)
    {
        if($question)
        {
            if($question->isRootQuestion()){
                $allQuestions = $this->questionRepository->findBy([
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
