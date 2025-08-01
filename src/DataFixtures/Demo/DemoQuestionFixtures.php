<?php

namespace App\DataFixtures\Demo;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Question; 

class DemoQuestionFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['demo'];
    }

    public function load(ObjectManager $manager): void
    {
        // Tree one

        $question = new Question();
        $question->setName('focus_moment_day');
        $question->setContent('À quel moment de la journée as-tu le plus de mal à te concentrer ?'); 
        $question->setIsRootQuestion(true); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("focus_moment_day", $question);

        // -----------------------------------------------------

        $question = new Question();
        $question->setName('hard_morning');
        $question->setContent('As-tu du mal à te réveiller le matin ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("hard_morning", $question);
        
        // -----------------------------------------------------


        $question = new Question();
        $question->setName('breakfast');
        $question->setContent('Est-ce que tu prends un petit-déjeuner ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("breakfast", $question);

        // -----------------------------------------------------

        $question = new Question();
        $question->setName('afternoon_break');
        $question->setContent('Est-ce que tu fais des pauses dans l\'après-midi ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("afternoon_break", $question);

        // -----------------------------------------------------

        $question = new Question();
        $question->setName('focus_break');
        $question->setContent('Tes pauses te permettent-elles de recharger ton attention ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("focus_break", $question);
        
        // -----------------------------------------------------

        $question = new Question();
        $question->setName('hard_sleep');
        $question->setContent('As-tu du mal à t\'endormir ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("hard_sleep", $question);     
        

        // Tree two

        $question = new Question();
        $question->setName('have_animal');
        $question->setContent('Est-ce que tu as un animal ?'); 
        $question->setIsRootQuestion(true); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("have_animal", $question);       
        
        // -----------------------------------------------------

        $question = new Question();
        $question->setName('what_animal');
        $question->setContent('Quel type d\'animal as-tu ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("what_animal", $question);       
        
        // -----------------------------------------------------

        $question = new Question();
        $question->setName('dog_habits');
        $question->setContent('As-tu une routine de sortie pour ton chien ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("dog_habits", $question);       
        
        $manager->flush(); 

    }
}
