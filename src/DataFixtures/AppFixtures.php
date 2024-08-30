<?php

namespace App\DataFixtures;

use App\Entity\TemplateQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Question 1 : Fréquence de l'exercice physique
        $question1 = new TemplateQuestion();
        $question1->setName('Fréquence de l\'exercice physique');
        $question1->setContent('Combien de fois par semaine faites-vous de l\'exercice physique ?');
        $question1->setType('number');
        $question1->setPage(1);
        $question1->setCreationDate(new \DateTime());
        $manager->persist($question1);

        // Question 2 : Heures de réveil et de coucher habituelles
        $question2 = new TemplateQuestion();
        $question2->setName('Heures de réveil et de coucher habituelles');
        $question2->setContent('À quelle heure vous réveillez-vous et vous couchez-vous habituellement ?');
        $question2->setType('time');
        $question2->setPage(1);
        $question2->setCreationDate(new \DateTime());
        $manager->persist($question2);

        // Question 3 : Préférences de temps pour les tâches importantes
        $question3 = new TemplateQuestion();
        $question3->setName('Préférences de temps pour les tâches importantes');
        $question3->setContent('À quel moment de la journée préférez-vous accomplir les tâches les plus importantes ?');
        $question3->setType('radio');
        $question3->setChoice(['Matin', 'Après-midi', 'Soir']);
        $question3->setPage(2);
        $question3->setCreationDate(new \DateTime());
        $manager->persist($question3);

        // Question 4 : Temps dédié à la relaxation ou aux loisirs
        $question4 = new TemplateQuestion();
        $question4->setName('Temps dédié à la relaxation ou aux loisirs');
        $question4->setContent('Combien de temps par jour consacrez-vous à la relaxation ou à vos loisirs ?');
        $question4->setType('number');
        $question4->setPage(2);
        $question4->setCreationDate(new \DateTime());
        $manager->persist($question4);

        // Question 5 : Objectifs hebdomadaires prioritaires
        $question5 = new TemplateQuestion();
        $question5->setName('Objectifs hebdomadaires prioritaires');
        $question5->setContent('Quels sont vos objectifs prioritaires pour cette semaine ?');
        $question5->setType('textarea');
        $question5->setPage(3);
        $question5->setCreationDate(new \DateTime());
        $manager->persist($question5);

        // Question 6 : Type de repas préféré (pour les routines alimentaires)
        $question6 = new TemplateQuestion();
        $question6->setName('Type de repas préféré');
        $question6->setContent('Quel type de repas préférez-vous pour vos routines alimentaires ?');
        $question6->setType('checkbox');
        $question6->setChoice(['Petit-déjeuner léger', 'Déjeuner équilibré', 'Dîner copieux', 'Collation saine']);
        $question6->setPage(3);
        $question6->setCreationDate(new \DateTime());
        $manager->persist($question6);

        // Question 7 : Méthode de gestion du stress
        $question7 = new TemplateQuestion();
        $question7->setName('Méthode de gestion du stress');
        $question7->setContent('Quelle méthode utilisez-vous le plus souvent pour gérer le stress ?');
        $question7->setType('radio');
        $question7->setChoice(['Méditation', 'Exercice physique', 'Lecture', 'Autre']);
        $question7->setPage(4);
        $question7->setCreationDate(new \DateTime());
        $manager->persist($question7);

        $manager->flush();
    }
}
