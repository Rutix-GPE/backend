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

        //////////////////////

        $question2 = $this->getReference('question_2');

        $condition4 = new ConditionRoutine();
        $condition4->setName("Ménage la semaine");
        $condition4->setDescription("Je préfère faire mes tâches ménagères la semaine");
        $condition4->setTaskTime(new DateTime("5pm"));
        $condition4->setDays([1, 2, 3, 4, 5]);
        $condition4->setResponseCondition("Semaine");
        $condition4->setQuestion($question2);

        $manager->persist($condition4);


        $condition5 = new ConditionRoutine();
        $condition5->setName("Ménage le week-end");
        $condition5->setDescription("Je préfère faire mes tâches ménagères le week-end");
        $condition5->setTaskTime(new DateTime("5pm"));
        $condition5->setDays([6, 7]);
        $condition5->setResponseCondition("Week-end");
        $condition5->setQuestion($question2);

        $manager->persist($condition5);


        $condition6 = new ConditionRoutine();
        $condition6->setName("Aucun ménage");
        $condition6->setDescription("Je ne fais aucune tâches ménagère");
        $condition6->setTaskTime(new DateTime("5pm"));
        $condition6->setDays([]);
        $condition6->setResponseCondition("Jamais");
        $condition6->setQuestion($question2);

        $manager->persist($condition6);

        //////////////////////

        $question3 = $this->getReference('question_3');

        $condition7 = new ConditionRoutine();
        $condition7->setName("Matin léger");
        $condition7->setDescription("Je mange des petit-déjeuner léger");
        $condition7->setTaskTime(new DateTime("7am"));
        $condition7->setDays([1, 2, 3, 4, 5, 6, 7]);
        $condition7->setResponseCondition("Petit-déjeuner léger");
        $condition7->setQuestion($question3);

        $manager->persist($condition7);


        $condition8 = new ConditionRoutine();
        $condition8->setName("Pause équilibré");
        $condition8->setDescription("Je mange des repas équilibrés le midi");
        $condition8->setTaskTime(new DateTime("12pm"));
        $condition8->setDays([1, 2, 3, 4, 5, 6, 7]);
        $condition8->setResponseCondition("Déjeuner équilibré");
        $condition8->setQuestion($question3);

        $manager->persist($condition8);


        $condition9 = new ConditionRoutine();
        $condition9->setName("Soirée copieuse");
        $condition9->setDescription("J'aime bien manger le soir");
        $condition9->setTaskTime(new DateTime("7pm"));
        $condition9->setDays([1, 2, 3, 4, 5, 6, 7]);
        $condition9->setResponseCondition("Dîner copieux");
        $condition9->setQuestion($question3);

        $manager->persist($condition9);


        $condition10 = new ConditionRoutine();
        $condition10->setName("Repas sain");
        $condition10->setDescription("En général je mange de manière saine");
        $condition10->setTaskTime(new DateTime("12am"));
        $condition10->setDays([1, 2, 3, 4, 5, 6, 7]);
        $condition10->setResponseCondition("Collation saine");
        $condition10->setQuestion($question3);

        $manager->persist($condition10);


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TemplateQuestionFixtures::class,
        ];
    }

}
