<?php

namespace App\DataFixtures\Pres;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\RoutineV2;

class PresRoutineFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['pres'];
    }

    public function load(ObjectManager $manager): void
    {
        // Tree one

        $routine = new RoutineV2();
        $routine->setName('etude_fixe');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('08:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("etude_fixe", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('travail_fixe');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('09:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("travail_fixe", $routine);


        $manager->flush();

    }
}
