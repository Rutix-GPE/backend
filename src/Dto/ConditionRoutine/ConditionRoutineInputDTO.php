<?php

namespace App\Dto\ConditionRoutine;

use Symfony\Component\Validator\Constraints as Assert;

class ConditionRoutineInputDTO

{
    #[Assert\NotBlank()]
        public string $name;

        #[Assert\NotBlank()]
        public string $description;

        #[Assert\NotBlank()]
        #[Assert\Type(\DateTimeInterface::class)]
        public \DateTimeInterface $time;

        #[Assert\NotBlank()]
        public string $response;

        #[Assert\NotBlank()]
        public int $question;
    
        
}
