<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class TemplateQuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'required' => false,
                'label' => 'ID de la question (si nécessaire)',
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Nom de la question',
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Contenu de la question',
            ])
            ->add('type', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Texte' => 'text',
                    'Choix multiples' => 'multiple_choice',
                ],
                'label' => 'Type de question',
            ])
            ->add('choice', TextareaType::class, [
                'required' => false,
                'label' => 'Choix (séparés par des virgules)',
            ])
            ->add('page', TextType::class, [
                'required' => false,
                'label' => 'Page de la question',
            ])
            ->add('action', ChoiceType::class, [
                'choices' => [
                    'Ajouter une question' => 'new',
                    'Afficher une question' => 'show',
                    'Lister toutes les questions' => 'list',
                    'Lister les questions par page' => 'list_by_page',
                    'Supprimer une question' => 'delete',
                ],
                'label' => 'Action',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Exécuter l\'action',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
