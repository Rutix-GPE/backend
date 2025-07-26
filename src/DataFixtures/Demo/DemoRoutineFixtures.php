<?php

namespace App\DataFixtures\Demo;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\RoutineV2;

class DemoRoutineFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['demo'];
    }

    public function load(ObjectManager $manager): void
    {
        // Tree one

        $routine = new RoutineV2();
        $routine->setName('reveil_stimulant');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('07:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("reveil_stimulant", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('pause_sensorielle');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('13:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("pause_sensorielle", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('deconnexion_numerique');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("deconnexion_numerique", $routine);


        // Tree two

        $routine = new RoutineV2();
        $routine->setName('entretien_animal');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("entretien_animal", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('entretien_chat');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("entretien_chat", $routine);

        // -----------------------------------------------------   
        
        $routine = new RoutineV2();
        $routine->setName('entretien_chien');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("entretien_chien", $routine);


        $manager->flush();

    }
}
