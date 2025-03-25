<?php 

namespace App\Service;

use App\Entity\TemplateQuestion;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use App\Repository\UserResponseRepository;

class UserService 
{

    private UserRepository $userRepository;
    
    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

}
