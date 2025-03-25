<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'required' => false,
                'label' => 'User ID (pour les actions nécessitant un ID)',
            ])
            ->add('username', TextType::class, [
                'required' => false,
                'label' => 'Nom d\'utilisateur',
            ])
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => 'Nom de famille',
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'Email',
            ])
            ->add('phonenumber', TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
            ])
            ->add('role', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Utilisateur' => 'user',
                    'Administrateur' => 'admin',
                ],
                'label' => 'Rôle',
            ])
            ->add('memo', TextType::class, [
                'required' => false,
                'label' => 'Mémo utilisateur',
            ])
            ->add('action', ChoiceType::class, [
                'choices' => [
                    'Afficher un utilisateur' => 'show',
                    'Lister tous les utilisateurs' => 'list',
                    'Mettre à jour un utilisateur' => 'update',
                    'Modifier le rôle' => 'update_role',
                    'Supprimer un utilisateur' => 'delete',
                    'Mettre à jour le mémo' => 'update_memo',
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
