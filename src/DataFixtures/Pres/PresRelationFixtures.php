<?php

namespace App\DataFixtures\Pres;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Relation;
use App\Repository\QuestionRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Service\RelationService;

class PresRelationFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    public static function getGroups(): array
    {
        return ['pres'];
    }

    public function load(ObjectManager $manager): void
    {

        // Tree one

        $relation = new Relation();
        $relation->setSource($this->getReference("is_employed"));
        $relation->setTargetQuestion($this->getReference("is_student"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // -----------------------------------------------------

        $relation = new Relation();
        $relation->setSource($this->getReference("is_employed"));
        $relation->setTargetQuestion($this->getReference("employee_fixed_hours"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // -----------------------------------------------------  
        
        $relation = new Relation();
        $relation->setSource($this->getReference("is_student"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("is_student"));
        $relation->setTargetQuestion($this->getReference("student_fixed_hours"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("student_fixed_hours"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("student_fixed_hours"));
        $relation->setTargetRoutine($this->getReference("etude_fixe"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("employee_fixed_hours"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("employee_fixed_hours"));
        $relation->setTargetRoutine($this->getReference("travail_fixe"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // Tree two

        $relation = new Relation();
        $relation->setSource($this->getReference("are_you_sporty"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("are_you_sporty"));
        $relation->setTargetQuestion($this->getReference("what_rythme"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("what_rythme"));
        $relation->setTargetRoutine($this->getReference("sporty_7days"));
        $relation->setAnswer("7 fois par semaine");

        $manager->persist($relation);  
        
        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("what_rythme"));
        $relation->setTargetRoutine($this->getReference("sporty_4days"));
        $relation->setAnswer("4 fois par semaine");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("what_rythme"));
        $relation->setTargetRoutine($this->getReference("sporty_3days"));
        $relation->setAnswer("3 fois par semaine");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("what_rythme"));
        $relation->setTargetRoutine($this->getReference("sporty_2days"));
        $relation->setAnswer("2 fois par semaine");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("what_rythme"));
        $relation->setTargetRoutine($this->getReference("sporty_1days"));
        $relation->setAnswer("1 fois par semaine");

        $manager->persist($relation);

        // Tree three

        $relation = new Relation();
        $relation->setSource($this->getReference("treatment_?"));
        $relation->setTargetQuestion($this->getReference("vitamin_?"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("vitamin_?"));
        $relation->setAnswer("Non");

        $manager->persist($relation);        

        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("vitamin_?"));
        $relation->setTargetRoutine($this->getReference("vitamin"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);      
        
        // ----------------------------------------------------- 

        $relation = new Relation();
        $relation->setSource($this->getReference("treatment_?"));
        $relation->setTargetRoutine($this->getReference("treatment"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);     
        
        // Tree four

        $relation = new Relation();
        $relation->setSource($this->getReference("focus_moment_day"));
        $relation->setTargetQuestion($this->getReference("hard_morning"));
        $relation->setAnswer("Matin");

        $manager->persist($relation);

        // -----------------------------------------------------

        $relation = new Relation();
        $relation->setSource($this->getReference("hard_morning"));
        $relation->setTargetRoutine($this->getReference("reveil_stimulant"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // -----------------------------------------------------

        $relation = new Relation();
        $relation->setSource($this->getReference("hard_morning"));
        $relation->setTargetQuestion($this->getReference("breakfast"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // -----------------------------------------------------

        $relation = new Relation();
        $relation->setSource($this->getReference("breakfast"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);  
        
        // -----------------------------------------------------

        $relation = new Relation();
        $relation->setSource($this->getReference("breakfast"));
        $relation->setTargetRoutine($this->getReference("reveil_stimulant"));
        $relation->setAnswer("Non");

        $manager->persist($relation);  

        // -----------------------------------------------------
        
        $relation = new Relation();
        $relation->setSource($this->getReference("focus_moment_day"));
        $relation->setTargetQuestion($this->getReference("afternoon_break"));
        $relation->setAnswer("Après-midi");

        $manager->persist($relation);

        // -----------------------------------------------------
        
        $relation = new Relation();
        $relation->setSource($this->getReference("afternoon_break"));
        $relation->setTargetQuestion($this->getReference("focus_break"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // -----------------------------------------------------
        
        $relation = new Relation();
        $relation->setSource($this->getReference("afternoon_break"));
        $relation->setTargetRoutine($this->getReference("pause_sensorielle"));
        $relation->setAnswer("Non");

        $manager->persist($relation);  
        
        // -----------------------------------------------------
        
        $relation = new Relation();
        $relation->setSource($this->getReference("focus_break"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // -----------------------------------------------------
        
        $relation = new Relation();
        $relation->setSource($this->getReference("focus_break"));
        $relation->setTargetRoutine($this->getReference("pause_sensorielle"));
        $relation->setAnswer("Non");

        $manager->persist($relation); 

        // -----------------------------------------------------

        $relation = new Relation();
        $relation->setSource($this->getReference("focus_moment_day"));
        $relation->setTargetQuestion($this->getReference("hard_sleep"));
        $relation->setAnswer("Soir");

        $manager->persist($relation);  
        
        // -----------------------------------------------------
        
        $relation = new Relation();
        $relation->setSource($this->getReference("hard_sleep"));
        $relation->setTargetRoutine($this->getReference("deconnexion_numerique"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);  
        
        // -----------------------------------------------------
        
        $relation = new Relation();
        $relation->setSource($this->getReference("hard_sleep"));
        $relation->setAnswer("Non");

        $manager->persist($relation);        


        // Tree five
        
        $relation = new Relation();
        $relation->setSource($this->getReference("have_animal"));
        $relation->setTargetQuestion($this->getReference("what_animal"));
        $relation->setAnswer("Oui");

        $manager->persist($relation); 

        // -----------------------------------------------------
                
        $relation = new Relation();
        $relation->setSource($this->getReference("have_animal"));
        $relation->setAnswer("Non");

        $manager->persist($relation); 

        // -----------------------------------------------------
                
        $relation = new Relation();
        $relation->setSource($this->getReference("what_animal"));
        $relation->setTargetQuestion($this->getReference("dog_habits"));
        $relation->setAnswer("Chien");

        $manager->persist($relation); 

        // -----------------------------------------------------
                
        $relation = new Relation();
        $relation->setSource($this->getReference("what_animal"));
        $relation->setTargetRoutine($this->getReference("entretien_chat"));
        $relation->setAnswer("Chat");

        $manager->persist($relation);      
        
        // -----------------------------------------------------
                
        $relation = new Relation();
        $relation->setSource($this->getReference("what_animal"));
        $relation->setTargetRoutine($this->getReference("entretien_animal"));
        $relation->setAnswer("Autre");

        $manager->persist($relation);  

        // -----------------------------------------------------
                
        $relation = new Relation();
        $relation->setSource($this->getReference("dog_habits"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);  

        // -----------------------------------------------------
                
        $relation = new Relation();
        $relation->setSource($this->getReference("dog_habits"));
        $relation->setTargetRoutine($this->getReference("entretien_chien"));
        $relation->setAnswer("Non");

        $manager->persist($relation);


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PresQuestionFixtures::class,
            PresRoutineFixtures::class,
        ];
    }
}
