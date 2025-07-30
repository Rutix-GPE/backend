<?php

namespace App\Tests\Service;

use App\Service\UserTaskService;
use App\Entity\User;
use App\Entity\UserRoutine;
use App\Entity\UserTask;
use App\Repository\UserTaskRepository;
use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use PHPUnit\Framework\TestCase;

class UserTaskServiceTest extends TestCase
{
    private $userTaskRepository;
    private $service;

    protected function setUp(): void
    {
        $this->userTaskRepository = $this->createMock(UserTaskRepository::class);
        $this->service = new UserTaskService($this->userTaskRepository);
    }

    private function createUser($id = 1)
    {
        $user = new User();
        $ref = new \ReflectionClass($user);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($user, $id);
        $user->setUsername('testuser' . $id);
        $user->setEmail('user' . $id . '@test.com');
        $user->setFirstname('User');
        $user->setLastname('Test');
        $user->setPassword('pass');
        return $user;
    }

    private function createUserRoutine($user = null, $days = ['1', '2', '3'], $time = null)
    {
        $routine = new UserRoutine();
        $routine->setName('Morning Routine');
        $routine->setDescription('desc');
        $routine->setDays($days);
        $routine->setTaskTime($time ?: new \DateTime('07:00:00'));
        $routine->setIsAllTaskGenerated(false);
        $routine->setUser($user ?: $this->createUser());
        $routine->setCreationDate(new \DateTime());
        $routine->setUpdatedDate(new \DateTime());
        return $routine;
    }

    private function createUserTask($user = null, $datetime = null)
    {
        $task = new UserTask();
        $task->setName('task');
        $task->setDescription('desc');
        $task->setTaskDateTime($datetime ?: new \DateTime());
        $task->setStatus(false);
        $task->setUser($user ?: $this->createUser());
        $task->setCreationDate(new \DateTime());
        $task->setUpdatedDate(new \DateTime());
        return $task;
    }

    public function testGetTaskByIdReturnsTask()
    {
        $task = $this->createUserTask();
        $this->userTaskRepository->method('findOneBy')->with(['id' => 10])->willReturn($task);
        $result = $this->service->getTaskById(10);
        $this->assertSame($task, $result);
    }

    public function testCreateOneAddsTask()
    {
        $routine = $this->createUserRoutine();
        $date = '2023-01-15';
        $this->userTaskRepository->expects($this->once())->method('add');
        $this->service->createOne($routine, $date);
    }

    public function testConcatDateTimeBuildsValidDatetime()
    {
        $routine = $this->createUserRoutine();
        $date = '2024-02-18';
        $result = $this->service->concatDateTime($routine->getTaskTime(), $date);
        $this->assertInstanceOf(\DateTime::class, $result);
        $this->assertEquals('2024-02-18 ' . $routine->getTaskTime()->format('H:i:s'), $result->format('Y-m-d H:i:s'));
    }

    public function testControllerUpdateTaskUpdatesFields()
    {
        $task = $this->createUserTask();
        $data = [
            'name' => 'NewName',
            'description' => 'desc2',
            'taskDateTime' => '2025-06-01 14:30:00',
            'status' => true,
        ];
        $this->userTaskRepository->expects($this->once())->method('add');
        $result = $this->service->controllerUpdateTask($task, $data);
        $this->assertSame($task, $result);
        $this->assertEquals('NewName', $task->getName());
        $this->assertEquals('desc2', $task->getDescription());
        $this->assertInstanceOf(\DateTime::class, $task->getTaskDateTime());
        $this->assertEquals(true, $task->getStatus());
    }

    public function testControllerCreateTaskCreatesAndAddsTask()
    {
        $user = $this->createUser();
        $data = [
            'name' => 'DoStuff',
            'description' => 'desc',
            'taskDateTime' => new \DateTime('2025-02-02 07:30:00'),
        ];
        $this->userTaskRepository->expects($this->once())->method('add');
        $task = $this->service->controllerCreateTask($user, $data);
        $this->assertInstanceOf(UserTask::class, $task);
        $this->assertEquals('DoStuff', $task->getName());
        $this->assertEquals($user, $task->getUser());
    }

    public function testControllerGetTasksByUserReturnsTasks()
    {
        $user = $this->createUser();
        $tasks = [$this->createUserTask($user)];
        $this->userTaskRepository->method('findBy')->with(['user' => $user])->willReturn($tasks);
        $result = $this->service->controllerGetTasksByUser($user);
        $this->assertEquals($tasks, $result);
    }

    public function testControllerGetTasksByUserAndDateTime()
    {
        $user = $this->createUser(1);
        $date = new \DateTime('2025-02-15 09:00:00');
        $tasks = [$this->createUserTask($user, $date)];
        $this->userTaskRepository->method('findBy')->with(['user' => $user->id, 'taskDateTime' => $date])->willReturn($tasks);
        $result = $this->service->controllerGetTasksByUserAndDateTime($user, $date);
        $this->assertEquals($tasks, $result);
    }

    public function testControllerGetTasksByUserAndDateFiltersCorrectly()
    {
        $user = $this->createUser();
        $targetDate = new \DateTime('2024-01-01');
        $task1 = $this->createUserTask($user, new \DateTime('2024-01-01 08:00:00'));
        $task2 = $this->createUserTask($user, new \DateTime('2024-01-02 09:00:00'));
        $this->userTaskRepository->method('findBy')->with(['user' => $user])->willReturn([$task1, $task2]);
        $res = $this->service->controllerGetTasksByUserAndDate($user, $targetDate);
        $this->assertCount(1, $res);
        $this->assertEquals('2024-01-01', $res[0]->getTaskDateTime()->format('Y-m-d'));
    }

    public function testControllerDeleteTaskRemovesTask()
    {
        $task = $this->createUserTask();
        $this->userTaskRepository->method('findOneBy')->with(['id' => 42])->willReturn($task);
        $this->userTaskRepository->expects($this->once())->method('remove');
        $this->service->controllerDeleteTask(42);
    }

    public function testControllerDeleteTaskThrowsIfNotFound()
    {
        $this->userTaskRepository->method('findOneBy')->willReturn(null);
        $this->expectException(BadRequestHttpException::class);
        $this->service->controllerDeleteTask(999);
    }
}

