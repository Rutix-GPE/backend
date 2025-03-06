<?php
namespace App\Dto;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Username is required']),
                //     new Assert\Length([
                //         'min' => 2,
                //         'max' => 50,
                //         'minMessage' => 'Username must be at least 4 characters long',
                //         'maxMessage' => 'Username cannot be longer than 50 characters'
                //     ]),
                // ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'First name is required']),
                // ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Last name is required']),
                // ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            //     'constraints' => [
            //         new Assert\NotBlank(['message' => 'Email is required']),
            //         new Assert\Email(['message' => 'Please enter a valid email address'])
            //     ]
            ])
            ->add('phonenumber', TelType::class, [
                'label' => 'Phone Number',
                // 'required' => false,
                // 'constraints' => [
                //     new Assert\Length([
                //         'max' => 15,
                //         'maxMessage' => 'Phone number cannot be longer than 15 digits'
                //     ])
                // ]
            ])
            ->add('country', TextType::class, [
                'label' => 'Country',
                'required' => false,
            ])
            ->add('postalcode', TextType::class, [
                'label' => 'Postal Code',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => false,
            ])
            ->add('adress', TextType::class, [
                'label' => 'Address',
                'required' => false,
            ])
            ->add('memo', TextType::class, [
                'label' => 'Memo',
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Password is required']),
                //     new Assert\Length([
                //         'min' => 3,
                //         'minMessage' => 'Password must be at least 6 characters long'
                //     ]),
                // ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,  // Lier le formulaire à l'entité User
        ]);
    }
}
