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

}
