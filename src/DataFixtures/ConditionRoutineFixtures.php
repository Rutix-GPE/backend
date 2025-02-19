<?php

namespace App\DataFixtures;

use App\Entity\ConditionRoutine;
use App\Repository\TemplateQuestionRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;

class ConditionRoutineFixtures extends Fixture implements DependentFixtureInterface
{

    private $templateQuestionRepository;
    public function __construct(TemplateQuestionRepository $templateQuestionRepository)
    {
        $this->templateQuestionRepository = $templateQuestionRepository;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $question1 = $this->getReference('question_1');

        $condition1 = new ConditionRoutine();
        $condition1->setName("Je suis débutant");
        $condition1->setDescription("Je fais du sport entre 1 à 3 fois par semaine");
        $condition1->setTaskTime(new DateTime("2pm"));
        $condition1->setDays([1, 4, 6]);
        $condition1->setResponseCondition("1-2-3");
        $condition1->setQuestion($question1);

        $manager->persist($condition1);


        $condition2 = new ConditionRoutine();
        $condition2->setName("Je suis intermédiare");
        $condition2->setDescription("Je fais du sport entre 4 à 6 fois par semaine");
        $condition2->setTaskTime(new DateTime("2pm"));
        $condition2->setDays([1, 3, 4, 5, 7]);
        $condition2->setResponseCondition("4-5-6");
        $condition2->setQuestion($question1);

        $manager->persist($condition2);


        $condition3 = new ConditionRoutine();
        $condition3->setName("Je suis professionnel");
        $condition3->setDescription("Je fais du sport plus de 7 fois par semaine");
        $condition3->setTaskTime(new DateTime("2pm"));
        $condition3->setDays([1, 2, 3, 4, 5, 6, 7]);
        $condition3->setResponseCondition("7+");
        $condition3->setQuestion($question1);

        $manager->persist($condition3);



        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TemplateQuestionFixtures::class,
        ];
    }

}
