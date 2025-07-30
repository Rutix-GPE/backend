<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Entity\UserTask;
use App\Repository\UserRepository;
use App\Repository\UserTaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTaskRepositoryTest extends KernelTestCase
{
    private $userRepository;
    private $userTaskRepository;
    private $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->userRepository = $container->get(UserRepository::class);
        $this->userTaskRepository = $container->get(UserTaskRepository::class);
        $this->em = $container->get('doctrine')->getManager();

        // Nettoyage pour éviter les collisions
        $connection = $this->em->getConnection();
        $connection->executeStatement('DELETE FROM user_task');
        $connection->executeStatement('DELETE FROM user_routine');
        $connection->executeStatement('DELETE FROM user');
        $connection->executeStatement('ALTER TABLE user_task AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE user AUTO_INCREMENT = 1');
    }

    public function testAddAndFindUserTask()
    {
        $user = new User();
        $user->setUsername('taskuser');
        $user->setPassword('password');
        $user->setEmail('taskuser@email.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $this->userRepository->add($user, true);

        $task = new UserTask();
        $task->setName('Test Task');
        $task->setDescription('Description de la tâche');
        $task->setTaskDateTime(new \DateTime('2025-01-01 10:00:00'));
        $task->setStatus(1);
        $task->setUser($user);

        $this->userTaskRepository->add($task, true);

        $found = $this->userTaskRepository->find($task->getId());
        $this->assertInstanceOf(UserTask::class, $found);
        $this->assertSame('Test Task', $found->getName());
        $this->assertSame('Description de la tâche', $found->getDescription());
        $this->assertSame($user->getId(), $found->getUser()->getId());
    }

    public function testUpdateAndRemoveUserTask()
    {
        $user = new User();
        $user->setUsername('usertask2');
        $user->setPassword('pass');
        $user->setEmail('usertask2@email.com');
        $user->setFirstname('Jane');
        $user->setLastname('Doe');
        $this->userRepository->add($user, true);

        $task = new UserTask();
        $task->setName('TaskToUpdate');
        $task->setDescription('Desc');
        $task->setTaskDateTime(new \DateTime('2025-02-02 12:00:00'));
        $task->setStatus(0);
        $task->setUser($user);
        $this->userTaskRepository->add($task, true);

        // Update
        $task->setStatus(2);
        $task->setName('UpdatedTask');
        $this->userTaskRepository->add($task, true);

        $found = $this->userTaskRepository->find($task->getId());
        $this->assertSame(2, $found->getStatus());
        $this->assertSame('UpdatedTask', $found->getName());

        // Remove
        $id = $task->getId();
        $this->userTaskRepository->remove($task, true);
        $deleted = $this->userTaskRepository->find($id);
        $this->assertNull($deleted);
    }
}
