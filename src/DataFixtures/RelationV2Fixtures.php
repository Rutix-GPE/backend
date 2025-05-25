<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\RelationV2;
use App\Repository\QuestionV2Repository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Service\RelationV2Service;

class RelationV2Fixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    public static function getGroups(): array
    {
        return ['v2'];
    }

    public function load(ObjectManager $manager): void
    {

        // $relationService->join("focus_moment_day", "hard_morning", "question", "Matin");

        $relation = new RelationV2();
        $relation->setSource($this->getReference("focus_moment_day"));
        $relation->setTargetQuestion($this->getReference("hard_morning"));
        $relation->setAnswer("Matin");

        $manager->persist($relation);

        // -----------------------------------------------------

        $relation = new RelationV2();
        $relation->setSource($this->getReference("hard_morning"));
        $relation->setTargetRoutine($this->getReference("reveil_stimulant"));
        $relation->setAnswer("Oui");

        $manager->persist($relation);

        // -----------------------------------------------------

        $relation = new RelationV2();
        $relation->setSource($this->getReference("hard_morning"));
        $relation->setTargetQuestion($this->getReference("breakfast"));
        $relation->setAnswer("Non");

        $manager->persist($relation);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionV2Fixtures::class,
            RoutineV2Fixtures::class,
        ];
    }
}
