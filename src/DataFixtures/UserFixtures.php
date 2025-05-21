<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Utilisateur 1 
        $user1 = new User();
        $user1->setUsername('JohnDoe');
        $user1->setFirstname('John');
        $user1->setLastname('Doe');
        $user1->setEmail('johndoe@example.com');
        $user1->setPhonenumber('1234567890');
        $user1->setCountry('FR');
        $user1->setPostalcode('75001');
        $user1->setCity('Paris');
        $user1->setAdress('1 Rue de Paris');

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user1,
            'azerty123'
        );

        $user1->setPassword($hashedPassword);
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user1);

        // Utilisateur 2 
        $user2 = new User();
        $user2->setUsername('MaxSmith');
        $user2->setFirstname('Max');
        $user2->setLastname('Smith');
        $user2->setEmail('janesmith@example.com');
        $user2->setPhonenumber('0987654321');
        $user2->setCountry('FR');
        $user2->setPostalcode('69002');
        $user2->setCity('Lyon');
        $user2->setAdress('10 Rue de Lyon');

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user2,
            'qwerty123'
        );
        $user2->setPassword($hashedPassword);
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        // Flush data to the database
        $manager->flush();
    }
}