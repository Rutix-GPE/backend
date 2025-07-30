<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Entity\UserRoutine;
use App\Repository\UserRoutineRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRoutineRepositoryTest extends KernelTestCase
{
    private $userRoutineRepository;
    private $userRepository;
    private $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->userRoutineRepository = $container->get(UserRoutineRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->em = $container->get('doctrine')->getManager();

        // On clean les tables nécessaires
        $connection = $this->em->getConnection();
        $connection->executeStatement('DELETE FROM user_routine');
        $connection->executeStatement('DELETE FROM user');
        $connection->executeStatement('ALTER TABLE user_routine AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE user AUTO_INCREMENT = 1');
    }

    public function testAddAndFindUserRoutine()
    {
        // Création d'un user obligatoire pour la relation
        $user = new User();
        $user->setUsername('uruser');
        $user->setPassword('secret');
        $user->setEmail('uruser@test.fr');
        $user->setFirstname('A');
        $user->setLastname('B');
        $this->userRepository->add($user, true);

        $routine = new UserRoutine();
        $routine->setName('Routine 1');
        $routine->setDescription('Desc');
        $routine->setDays([1, 2, 3]);
        $routine->setTaskTime(new \DateTime('08:30'));
        $routine->setIsAllTaskGenerated(false);
        $routine->setUser($user);

        $this->userRoutineRepository->add($routine, true);

        $found = $this->userRoutineRepository->find($routine->getId());
        $this->assertInstanceOf(UserRoutine::class, $found);
        $this->assertSame('Routine 1', $found->getName());
        $this->assertSame($user->getId(), $found->getUser()->getId());
    }

    public function testUpdateUserRoutine()
    {
        $user = new User();
        $user->setUsername('uruser2');
        $user->setPassword('secret');
        $user->setEmail('uruser2@test.fr');
        $user->setFirstname('X');
        $user->setLastname('Y');
        $this->userRepository->add($user, true);

        $routine = new UserRoutine();
        $routine->setName('Routine2');
        $routine->setDescription('Desc2');
        $routine->setDays([1, 2]);
        $routine->setTaskTime(new \DateTime('09:00'));
        $routine->setIsAllTaskGenerated(true);
        $routine->setUser($user);
        $this->userRoutineRepository->add($routine, true);

        // Mise à jour
        $routine->setDescription('Updated desc');
        $routine->setIsAllTaskGenerated(false);
        $this->userRoutineRepository->add($routine, true);

        $found = $this->userRoutineRepository->find($routine->getId());
        $this->assertSame('Updated desc', $found->getDescription());
        $this->assertFalse($found->isAllTaskGenerated());
    }
}
