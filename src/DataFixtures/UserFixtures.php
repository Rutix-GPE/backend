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
        // Utilisateur 1 : Rôle utilisateur standard
        $user1 = new User();
        $user1->setUsername('user1');
        $user1->setFirstname('John');
        $user1->setLastname('Doe');
        $user1->setEmail('johndoe@example.com');
        $user1->setPhonenumber('1234567890');
        $user1->setCountry('FR');
        $user1->setPostalcode('75001');
        $user1->setCity('Paris');
        $user1->setAdress('1 Rue de Paris');
        $user1->setCreationDate(new \DateTime());
        $user1->setUpdatedDate(new \DateTime());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user1,
            'password123'
        );
        $user1->setPassword($hashedPassword);
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user1);

        // Utilisateur 2 : Rôle administrateur
        $user2 = new User();
        $user2->setUsername('admin1');
        $user2->setFirstname('Jane');
        $user2->setLastname('Smith');
        $user2->setEmail('janesmith@example.com');
        $user2->setPhonenumber('0987654321');
        $user2->setCountry('FR');
        $user2->setPostalcode('69002');
        $user2->setCity('Lyon');
        $user2->setAdress('10 Rue de Lyon');
        $user2->setCreationDate(new \DateTime());
        $user2->setUpdatedDate(new \DateTime());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user2,
            'adminpassword123'
        );
        $user2->setPassword($hashedPassword);
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);

        // Utilisateur 3 : Rôle utilisateur avec des données différentes
        $user3 = new User();
        $user3->setUsername('user2');
        $user3->setFirstname('Alice');
        $user3->setLastname('Brown');
        $user3->setEmail('alicebrown@example.com');
        $user3->setPhonenumber('1122334455');
        $user3->setCountry('FR');
        $user3->setPostalcode('13001');
        $user3->setCity('Marseille');
        $user3->setAdress('20 Rue de Marseille');
        $user3->setCreationDate(new \DateTime());
        $user3->setUpdatedDate(new \DateTime());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user3,
            'password456'
        );
        $user3->setPassword($hashedPassword);
        $user3->setRoles(['ROLE_USER']);
        $manager->persist($user3);

        // Flush data to the database
        $manager->flush();
    }
}
