<?php

namespace App\DataFixtures;

use App\Entity\TemplateQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TemplateQuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Question 1 : Genre
        $question1 = new TemplateQuestion();
        $question1->setName('Genre');
        $question1->setContent('Quel est votre genre ?');
        $question1->setType('multiple_choice');
        $question1->setChoice(["Homme", "Femme", "Autre", "Préfère ne pas répondre"]);
        $manager->persist($question1);

        $this->addReference('question_1', $question1);

        // Question 2 : Type de neuroatypie
        $question2 = new TemplateQuestion();
        $question2->setName('Type de neuroatypie');
        $question2->setContent('Quel type de neuroatypie avez-vous (si vous souhaitez partager) ?');
        $question2->setType('multiple_choice');
        $question2->setChoice(["Trouble du spectre autistique (TSA)", "Trouble du déficit de l’attention avec ou sans hyperactivité (TDAH)", "Dyslexie", "Trouble de l’anxiété sociale", "Autre", "Préfère ne pas répondre"]);
        $manager->persist($question2);

        $this->addReference('question_2', $question2);

        // Question 3 : Heure de réveil
        $question3 = new TemplateQuestion();
        $question3->setName('Heure de réveil');
        $question3->setContent('À quelle heure vous levez-vous habituellement ?');
        $question3->setType('multiple_choice');
        $question3->setChoice(["Avant 6h", "Entre 6h et 8h", "Entre 8h et 10h", "Après 10h"]);
        $manager->persist($question3);

        $this->addReference('question_3', $question3);

        // Question 4 : Routine matinale
        $question4 = new TemplateQuestion();
        $question4->setName('Routine matinale');
        $question4->setContent('Avez-vous une routine bien définie pour commencer votre journée ?');
        $question4->setType('multiple_choice');
        $question4->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question4);

        $this->addReference('question_4', $question4);

        // Question 5 : Niveaux d'énergie
        $question5 = new TemplateQuestion();
        $question5->setName('Niveaux d\'énergie');
        $question5->setContent('Comment décririez-vous vos niveaux d’énergie tout au long de la journée ?');
        $question5->setType('multiple_choice');
        $question5->setChoice(["Hauts le matin, bas l’après-midi", "Bas le matin, hauts l’après-midi", "Constamment bas", "Constamment élevés", "Variable"]);
        $manager->persist($question5);

        $this->addReference('question_5', $question5);

        // Question 6 : Préférences alimentaires
        $question6 = new TemplateQuestion();
        $question6->setName('Préférences alimentaires');
        $question6->setContent('Avez-vous des préférences ou des restrictions alimentaires spécifiques ?');
        $question6->setType('multiple_choice');
        $question6->setChoice(["Oui", "Non"]);
        $manager->persist($question6);

        $this->addReference('question_6', $question6);

        // Question 7 : Gestion des repas
        $question7 = new TemplateQuestion();
        $question7->setName('Gestion des repas');
        $question7->setContent('Avez-vous du mal à gérer la préparation des repas ?');
        $question7->setType('multiple_choice');
        $question7->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question7);

        $this->addReference('question_7', $question7);

        // Question 8 : Heures des repas
        $question8 = new TemplateQuestion();
        $question8->setName('Heures des repas');
        $question8->setContent('Mangez-vous à des heures régulières ?');
        $question8->setType('multiple_choice');
        $question8->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question8);

        $this->addReference('question_8', $question8);

        // Question 9 : Problèmes de sommeil
        $question9 = new TemplateQuestion();
        $question9->setName('Problèmes de sommeil');
        $question9->setContent('Avez-vous des difficultés à vous endormir ou à rester endormi(e) ?');
        $question9->setType('multiple_choice');
        $question9->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question9);

        $this->addReference('question_9', $question9);

        // Question 10 : Durée du sommeil
        $question10 = new TemplateQuestion();
        $question10->setName('Durée du sommeil');
        $question10->setContent('Dormez-vous en moyenne combien d\'heures par nuit ?');
        $question10->setType('multiple_choice');
        $question10->setChoice(["Moins de 5 heures", "5 à 6 heures", "6 à 8 heures", "Plus de 8 heures"]);
        $manager->persist($question10);

        $this->addReference('question_10', $question10);

        // Question 11 : Sommeil réparateur
        $question11 = new TemplateQuestion();
        $question11->setName('Sommeil réparateur');
        $question11->setContent('Votre sommeil est-il réparateur ?');
        $question11->setType('multiple_choice');
        $question11->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question11);

        $this->addReference('question_11', $question11);

        // Question 12 : Routine d'hygiène
        $question12 = new TemplateQuestion();
        $question12->setName('Routine d\'hygiène');
        $question12->setContent('Avez-vous des difficultés à maintenir une routine régulière d’hygiène personnelle (douche, brossage des dents, etc.) ?');
        $question12->setType('multiple_choice');
        $question12->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question12);

        $this->addReference('question_12', $question12);

        // Question 13 : Fréquence des douches
        $question13 = new TemplateQuestion();
        $question13->setName('Fréquence des douches');
        $question13->setContent('À quelle fréquence prenez-vous une douche ou un bain ?');
        $question13->setType('multiple_choice');
        $question13->setChoice(["Tous les jours", "Tous les 2 à 3 jours", "Une fois par semaine", "Moins souvent"]);
        $manager->persist($question13);

        $this->addReference('question_13', $question13);

        // Question 14 : Fréquence du brossage des dents
        $question14 = new TemplateQuestion();
        $question14->setName('Fréquence du brossage des dents');
        $question14->setContent('À quelle fréquence vous brossez-vous les dents ?');
        $question14->setType('multiple_choice');
        $question14->setChoice(["Matin et soir", "Une fois par jour", "Quelques fois par semaine", "Rarement"]);
        $manager->persist($question14);

        $this->addReference('question_14', $question14);

        // Question 15 : Utilisation de rappels pour l'hygiène
        $question15 = new TemplateQuestion();
        $question15->setName('Utilisation de rappels pour l\'hygiène');
        $question15->setContent('Utilisez-vous des rappels (alarmes, applications) pour vous aider à maintenir votre routine d’hygiène ?');
        $question15->setType('multiple_choice');
        $question15->setChoice(["Oui", "Non"]);
        $manager->persist($question15);

        $this->addReference('question_15', $question15);

        // Question 16 : Gestion des cheveux
        $question16 = new TemplateQuestion();
        $question16->setName('Gestion des cheveux');
        $question16->setContent('Avez-vous du mal à vous coiffer ou à entretenir vos cheveux ?');
        $question16->setType('multiple_choice');
        $question16->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question16);

        $this->addReference('question_16', $question16);

        // Question 17 : Fréquence du changement de vêtements
        $question17 = new TemplateQuestion();
        $question17->setName('Fréquence du changement de vêtements');
        $question17->setContent('À quelle fréquence changez-vous vos vêtements ?');
        $question17->setType('multiple_choice');
        $question17->setChoice(["Tous les jours", "Tous les 2 à 3 jours", "Une fois par semaine", "Moins souvent"]);
        $manager->persist($question17);

        $this->addReference('question_17', $question17);

        // Question 18 : Stratégies pour gérer le stress
        $question18 = new TemplateQuestion();
        $question18->setName('Stratégies pour gérer le stress');
        $question18->setContent('Avez-vous des stratégies pour gérer le stress ou l\'anxiété ?');
        $question18->setType('multiple_choice');
        $question18->setChoice(["Oui", "Non"]);
        $manager->persist($question18);

        $this->addReference('question_18', $question18);

        // Question 19 : Crises d'angoisse et surcharge sensorielle
        $question19 = new TemplateQuestion();
        $question19->setName('Crises d\'angoisse et surcharge sensorielle');
        $question19->setContent('Avez-vous des crises d\'angoisse ou des moments de surcharge sensorielle ?');
        $question19->setType('multiple_choice');
        $question19->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question19);

        $this->addReference('question_19', $question19);

        // Question 20 : Bien-être mental
        $question20 = new TemplateQuestion();
        $question20->setName('Bien-être mental');
        $question20->setContent('Pratiquez-vous des activités qui favorisent votre bien-être mental ? (ex. méditation, sport, art)');
        $question20->setType('multiple_choice');
        $question20->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question20);
        $this->addReference('question_20', $question20);

        // Question 21 : Fréquence des interactions sociales
        $question21 = new TemplateQuestion();
        $question21->setName('Fréquence des interactions sociales');
        $question21->setContent('Quelle est la fréquence de vos interactions sociales ?');
        $question21->setType('multiple_choice');
        $question21->setChoice(["Quotidienne", "Hebdomadaire", "Mensuelle", "Rarement"]);
        $manager->persist($question21);
        $this->addReference('question_21', $question21);

        // Question 22 : Confort dans les interactions sociales
        $question22 = new TemplateQuestion();
        $question22->setName('Confort dans les interactions sociales');
        $question22->setContent('Vous sentez-vous à l\'aise dans les interactions sociales ?');
        $question22->setType('multiple_choice');
        $question22->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question22);
        $this->addReference('question_22', $question22);

        // Question 23 : Sensibilité aux sons, lumières ou textures
        $question23 = new TemplateQuestion();
        $question23->setName('Sensibilité aux sons, lumières ou textures');
        $question23->setContent('Êtes-vous sensible à certains sons, lumières ou textures ?');
        $question23->setType('multiple_choice');
        $question23->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question23);
        $this->addReference('question_23', $question23);

        // Question 24 : Outils pour gérer les sensibilités sensorielles
        $question24 = new TemplateQuestion();
        $question24->setName('Outils pour gérer les sensibilités sensorielles');
        $question24->setContent('Avez-vous des objets ou des outils pour gérer les sensibilités sensorielles (ex. casque antibruit, lunettes de soleil) ?');
        $question24->setType('multiple_choice');
        $question24->setChoice(["Oui", "Non"]);
        $manager->persist($question24);
        $this->addReference('question_24', $question24);

        // Question 25 : Stratégies d’adaptation au quotidien
        $question25 = new TemplateQuestion();
        $question25->setName('Stratégies d’adaptation au quotidien');
        $question25->setContent('Quelles sont les stratégies ou adaptations que vous utilisez pour mieux gérer vos journées ?');
        $question25->setType('multiple_choice');
        $question25->setChoice(["Listes de tâches", "Calendrier visuel", "Applications pour l’organisation", "Aucune stratégie particulière", "Autres"]);
        $manager->persist($question25);
        $this->addReference('question_25', $question25);

        // Question 26 : Effort ou concentration pour certaines tâches quotidiennes
        $question26 = new TemplateQuestion();
        $question26->setName('Effort ou concentration pour certaines tâches quotidiennes');
        $question26->setContent('Est-ce que certaines tâches quotidiennes vous demandent plus d\'effort ou de concentration ? (ex. rangement, cuisine)');
        $question26->setType('multiple_choice');
        $question26->setChoice(["Oui", "Non", "Parfois"]);
        $manager->persist($question26);
        $this->addReference('question_26', $question26);

        $manager->flush();
    }
}
