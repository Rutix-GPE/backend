<?php

namespace App\DataFixtures\Pres;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\RelationV2;
use App\Repository\QuestionV2Repository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Service\RelationV2Service;

class PresRelationFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    public static function getGroups(): array
    {
        return ['pres'];
    }

    public function load(ObjectManager $manager): void
    {

        // Tree one

        $relation = new RelationV2();
        $relation->setSource($this->getReference("is_employed"));
        $relation->setTargetQuestion($this->getReference("is_student"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // -----------------------------------------------------

        $relation = new RelationV2();
        $relation->setSource($this->getReference("is_employed"));
        $relation->setTargetQuestion($this->getReference("employee_fixed_hours"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // -----------------------------------------------------  
        
        $relation = new RelationV2();
        $relation->setSource($this->getReference("is_student"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new RelationV2();
        $relation->setSource($this->getReference("is_student"));
        $relation->setTargetQuestion($this->getReference("student_fixed_hours"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new RelationV2();
        $relation->setSource($this->getReference("student_fixed_hours"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new RelationV2();
        $relation->setSource($this->getReference("student_fixed_hours"));
        $relation->setTargetRoutine($this->getReference("etude_fixe"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new RelationV2();
        $relation->setSource($this->getReference("employee_fixed_hours"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        // ----------------------------------------------------- 

        $relation = new RelationV2();
        $relation->setSource($this->getReference("employee_fixed_hours"));
        $relation->setTargetRoutine($this->getReference("travail_fixe"));
        $relation->setAnswer("Oui");

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
