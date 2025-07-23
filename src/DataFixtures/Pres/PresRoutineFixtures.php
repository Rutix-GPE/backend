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

        // Tree two

        $routine = new RoutineV2();
        $routine->setName('sporty_7days');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_7days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('sporty_4days');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 3, 5, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_4days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('sporty_3days');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 3, 6]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_3days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('sporty_2days');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 5]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_2days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('sporty_1days');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([3]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_1days", $routine);
        

        $manager->flush();

    }
}
