<?php

namespace App\DataFixtures\Pres;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\QuestionV2; 

class PresQuestionFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['pres'];
    }

    public function load(ObjectManager $manager): void
    {
        // Tree one

        $question = new QuestionV2();
        $question->setName('is_employed');
        $question->setContent('As-tu un emploi ?'); 
        $question->setIsRootQuestion(true); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("is_employed", $question);

        // -----------------------------------------------------

        $question = new QuestionV2();
        $question->setName('is_student');
        $question->setContent('Est-tu étudiant ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("is_student", $question);

        // -----------------------------------------------------

        $question = new QuestionV2();
        $question->setName('student_fixed_hours');
        $question->setContent('As-tu des horaires fixes ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("student_fixed_hours", $question);

        // -----------------------------------------------------

        $question = new QuestionV2();
        $question->setName('employee_fixed_hours');
        $question->setContent('As-tu des horaires fixes ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("employee_fixed_hours", $question);
  
        $manager->flush(); 

    }
}
