<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\QuestionV2; 

class QuestionV2Fixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['v2'];
    }

    public function load(ObjectManager $manager): void
    {
        $question = new QuestionV2();
        $question->setName('focus_moment_day');
        $question->setContent('À quel moment de la journée as-tu le plus de mal à te concentrer ?'); 
        $question->setIsRootQuestion(true); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("focus_moment_day", $question);
        // $manager->flush();

        // -----------------------------------------------------

        $question = new QuestionV2();
        $question->setName('hard_morning');
        $question->setContent('As-tu du mal à te réveiller le matin ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("hard_morning", $question);
        // $manager->flush();
        
        // -----------------------------------------------------


        $question = new QuestionV2();
        $question->setName('breakfast');
        $question->setContent('Est-ce que tu prends un petit-déjeuner ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("breakfast", $question);
        // $manager->flush();

        // -----------------------------------------------------

        $question = new QuestionV2();
        $question->setName('afternoon_break');
        $question->setContent('Est-ce que tu fais des pauses dans l\'après-midi ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("afternoon_break", $question);
        // $manager->flush();

        // -----------------------------------------------------

        $question = new QuestionV2();
        $question->setName('focus_break');
        $question->setContent('Tes pauses te permettent-elles de recharger ton attention ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("focus_break", $question);
        // $manager->flush();     
        
        // -----------------------------------------------------

        $question = new QuestionV2();
        $question->setName('hard_sleep');
        $question->setContent('As-tu du mal à t\'endormir ?'); 
        $question->setIsRootQuestion(false); 
        $question->setIsQuickQuestion(true); 
        
        $manager->persist($question);
        $this->addReference("hard_sleep", $question);


        $manager->flush();         
    }
}
