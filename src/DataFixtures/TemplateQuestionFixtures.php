<?php

namespace App\DataFixtures;

use App\Entity\TemplateQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TemplateQuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Question 1 : Fréquence de l'exercice physique
        $question1 = new TemplateQuestion();
        $question1->setName('Fréquence de l\'exercice physique');
        $question1->setContent('Combien de fois par semaine faites-vous de l\'exercice physique ?');
        $question1->setType('multiple_choice');
        $question1->setChoice(["1-2-3", "4-5-6", "7+"]);
        $manager->persist($question1);

        $this->addReference('question_1', $question1);

        // Question 2 : Moment pour tâches ménagères
        $question2 = new TemplateQuestion();
        $question2->setName('Préférences du  moment pour les tâches ménagères');
        $question2->setContent('À quel moment de la semaine préférez-vous faire vos tâches ménagères ?');
        $question2->setType('multiple_choice');
        $question2->setChoice(['Semaine', 'Week-end', 'Jamais']);
        $question2->setPage(2);
        $manager->persist($question2);

        $this->addReference('question_2', $question2);

        // Question 3 : Type de repas préféré (pour les routines alimentaires)
        $question3 = new TemplateQuestion();
        $question3->setName('Type de repas préféré');
        $question3->setContent('Quel type de repas préférez-vous pour vos routines alimentaires ?');
        $question3->setType('multiple_choice');
        $question3->setChoice(['Petit-déjeuner léger', 'Déjeuner équilibré', 'Dîner copieux', 'Collation saine']);
        $question3->setPage(2);
        $manager->persist($question3);

        $this->addReference('question_3', $question3);

        $manager->flush();
    }
}
