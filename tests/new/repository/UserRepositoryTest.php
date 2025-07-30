<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $userRepository;
    private $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->userRepository = $container->get(UserRepository::class);
        $this->em = $container->get('doctrine')->getManager();

        // On clean la table
        $connection = $this->em->getConnection();
        $connection->executeStatement('DELETE FROM user_task');
        $connection->executeStatement('DELETE FROM user_routine');
        $connection->executeStatement('DELETE FROM user');
        $connection->executeStatement('ALTER TABLE user AUTO_INCREMENT = 1');
    }

    public function testAddAndFindUser()
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setPassword('secret');
        $user->setEmail('testuser@example.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');

        $this->userRepository->add($user, true);

        // Test find
        $found = $this->userRepository->find($user->getId());
        $this->assertInstanceOf(User::class, $found);
        $this->assertSame('testuser', $found->getUsername());

        // Test findOneBy
        $byEmail = $this->userRepository->findOneBy(['email' => 'testuser@example.com']);
        $this->assertNotNull($byEmail);
        $this->assertSame('John', $byEmail->getFirstname());

        // Test findAll
        $all = $this->userRepository->findAll();
        $this->assertCount(1, $all);
    }

    public function testUpdateUser()
    {
        $user = new User();
        $user->setUsername('modifuser');
        $user->setPassword('secret');
        $user->setEmail('modif@example.com');
        $user->setFirstname('A');
        $user->setLastname('B');

        $this->userRepository->add($user, true);

        // Update
        $user->setFirstname('Alice');
        $this->userRepository->add($user, true);

        $found = $this->userRepository->findOneBy(['username' => 'modifuser']);
        $this->assertSame('Alice', $found->getFirstname());
    }

    public function testRemoveUser()
    {
        $user = new User();
        $user->setUsername('toremove');
        $user->setPassword('secret');
        $user->setEmail('rem@example.com');
        $user->setFirstname('Rem');
        $user->setLastname('Ove');

        $this->userRepository->add($user, true);

        $id = $user->getId();
        $this->userRepository->remove($user, true);

        $found = $this->userRepository->find($id);
        $this->assertNull($found);
    }

    public function testUpgradePassword()
    {
        $user = new User();
        $user->setUsername('pwduser');
        $user->setPassword('oldpass');
        $user->setEmail('pwd@example.com');
        $user->setFirstname('Pwd');
        $user->setLastname('User');
        $this->userRepository->add($user, true);

        $this->userRepository->upgradePassword($user, 'newpass');
        $found = $this->userRepository->findOneBy(['username' => 'pwduser']);
        $this->assertSame('newpass', $found->getPassword());
    }
}
