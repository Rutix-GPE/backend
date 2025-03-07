<?php 

namespace App\Service;

use App\Entity\TemplateQuestion;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserResponseRepository;

class UserResponseService 
{

    private UserResponseRepository $userResponseRepository;
    
    public function __construct(UserResponseRepository $userResponseRepository) 
    {
        $this->userResponseRepository = $userResponseRepository;
    }

}
