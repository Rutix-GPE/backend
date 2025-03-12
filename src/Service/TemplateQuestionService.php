<?php 

namespace App\Service;

use App\Entity\TemplateQuestion;
use App\Repository\TemplateQuestionRepository;

class TemplateQuestionService 
{

    private TemplateQuestionRepository $templateQuestionRepository;
    
    public function __construct(TemplateQuestionRepository $templateQuestionRepository) 
    {
        $this->templateQuestionRepository = $templateQuestionRepository;
    }

    public function controllerNew($data)
    {
        $question = new TemplateQuestion;

        $question->setName($data["name"]);
        $question->setContent($data["content"]);            
        $question->setType($data["type"]);
        $question->setChoice($data["choice"]);

        if(isset($data['page'])){
            $question->setPage($data['page']);
        }

        $this->templateQuestionRepository->add($question, true);

        return $question;
    }

    public function controllerShow($id)
    {
        return $this->templateQuestionRepository->find($id);

    }

    public function controllerList()
    {
        return $this->templateQuestionRepository->findAll();

    }

    public function controllerByPageQT($id)
    {
        return $this->templateQuestionRepository->findBy(['page' => $id]);
    }

}
