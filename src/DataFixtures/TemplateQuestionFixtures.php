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



        /* Les types de questions supportés sont les suivants :
         *
         * 1. 'text' : Permet de saisir une réponse sous forme de texte libre (champ de texte simple).
         * 2. 'textarea' : Permet de saisir une réponse sous forme de texte long (zone de texte étendue).
         * 3. 'number' : Permet de saisir une réponse numérique avec validation.
         * 4. 'time' : Permet de sélectionner une heure dans un format 24h (HH:mm).
         * 5. 'checkbox' : Permet de cocher plusieurs choix parmi une liste d'options. La réponse est un tableau des options sélectionnées.
         * 6. 'radio' : Permet de sélectionner une seule option parmi une liste de choix. La réponse est une seule valeur.
         *
         */

        // Question 1 : Fréquence de l'exercice physique
        $question1 = new TemplateQuestion();
        $question1->setName('Fréquence de l\'exercice physique');
        $question1->setContent('Combien de fois par semaine faites-vous de l\'exercice physique ?');
        $question1->setType('radio');
        $question1->setChoice(["1-2-3", "4-5-6", "7+"]);
        $manager->persist($question1);

        $this->addReference('question_1', $question1);

        // Question 2 : Moment pour tâches ménagères
        $question2 = new TemplateQuestion();
        $question2->setName('Préférences du  moment pour les tâches ménagères');
        $question2->setContent('À quel moment de la semaine préférez-vous faire vos tâches ménagères ?');
        $question2->setType('radio');
        $question2->setChoice(['Semaine', 'Week-end', 'Jamais']);
        $question2->setPage(2);
        $manager->persist($question2);

        $this->addReference('question_2', $question2);

        // Question 3 : Type de repas préféré (pour les routines alimentaires)
        $question3 = new TemplateQuestion();
        $question3->setName('Type de repas préféré');
        $question3->setContent('Quel type de repas préférez-vous pour vos routines alimentaires ?');
        $question3->setType('radio');
        $question3->setChoice(['Petit-déjeuner léger', 'Déjeuner équilibré', 'Dîner copieux', 'Collation saine']);
        $question3->setPage(2);
        $manager->persist($question3);

        $this->addReference('question_3', $question3);

        $manager->flush();
    }
}
