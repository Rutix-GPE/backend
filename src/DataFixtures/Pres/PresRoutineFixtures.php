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
        $routine->setName('Horaire étudiant');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('08:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("etude_fixe", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('Horaire travail');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('09:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("travail_fixe", $routine);

        // Tree two

        $routine = new RoutineV2();
        $routine->setName('7 jours de sport');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_7days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('4 jours de sport');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 3, 5, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_4days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('3 jours de sport');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 3, 6]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_3days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('2 jours de sport');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 5]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_2days", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('1 jours de sport');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([3]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("sporty_1days", $routine);
        
        // Tree three

        $routine = new RoutineV2();
        $routine->setName('Médicament');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('09:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("treatment", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('Vitamine');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('09:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("vitamin", $routine);

            // Tree four

        $routine = new RoutineV2();
        $routine->setName('Réveil stimulant');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('07:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("reveil_stimulant", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('Pause sensorielle');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5]); 
        $routine->setTaskTime(new \DateTime('13:30:00')); 
        
        $manager->persist($routine);
        $this->addReference("pause_sensorielle", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('Lecture du soir');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("deconnexion_numerique", $routine);


        // Tree five

        $routine = new RoutineV2();
        $routine->setName('Entretien animal');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("entretien_animal", $routine);

        // -----------------------------------------------------

        $routine = new RoutineV2();
        $routine->setName('Soin du chat');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("entretien_chat", $routine);

        // -----------------------------------------------------   
        
        $routine = new RoutineV2();
        $routine->setName('Sortie du chien');
        $routine->setDescription('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'); 
        $routine->setDays([1, 2, 3, 4, 5, 6, 7]); 
        $routine->setTaskTime(new \DateTime('20:00:00')); 
        
        $manager->persist($routine);
        $this->addReference("entretien_chien", $routine);

        $manager->flush();

    }
}
