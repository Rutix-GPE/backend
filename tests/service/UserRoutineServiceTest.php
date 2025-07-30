<?php

namespace App\Tests\Service;

use App\Entity\Routine;
use App\Entity\User;
use App\Repository\UserRoutineRepository;
use App\Service\UserRoutineService;
use App\Service\UserTaskService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UserRoutineServiceTest extends TestCase
{
    private $userRoutineRepository;
    private $userTaskService;
    private $service;

    protected function setUp(): void
    {
        $this->userRoutineRepository = $this->createMock(UserRoutineRepository::class);
        $this->userTaskService = $this->createMock(UserTaskService::class);

        $this->service = new UserRoutineService(
            $this->userRoutineRepository,
            $this->userTaskService
        );
    }

    public function testCopyRoutineCreatesNewUserRoutine()
    {
        $routine = $this->createMock(Routine::class);
        $user = $this->createMock(User::class);

        $routine->method('getName')->willReturn('Routine X');
        // Simule qu'il n'y a pas de doublon pour ce user/routine
        $this->userRoutineRepository->method('findOneBy')->willReturn(null);

        // Les deux appels à add (avant et après setIsAllTaskGenerated)
        $this->userRoutineRepository->expects($this->exactly(2))->method('add');
        $this->userTaskService->expects($this->once())->method('createList');

        $result = $this->service->copyRoutine($routine, $user);
        $this->assertEquals(Response::HTTP_CREATED, $result);
    }

    public function testCopyRoutineReturnsConflictIfDuplicate()
    {
        $routine = $this->createMock(Routine::class);
        $user = $this->createMock(User::class);

        $routine->method('getName')->willReturn('Routine X');
        // Simule qu'il existe déjà une UserRoutine avec ce nom pour cet user
        $this->userRoutineRepository->method('findOneBy')->willReturn(new \stdClass());

        // Dans ce cas, aucun add ni createList ne doit être appelé
        $this->userRoutineRepository->expects($this->never())->method('add');
        $this->userTaskService->expects($this->never())->method('createList');

        $result = $this->service->copyRoutine($routine, $user);
        $this->assertEquals(Response::HTTP_CONFLICT, $result);
    }

    public function testControllerGetRoutineByUser()
    {
        $userId = 42;
        $expected = ['test'];
        $this->userRoutineRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['user' => $userId])
            ->willReturn($expected);

        $result = $this->service->controllerGetRoutineByUser($userId);
        $this->assertSame($expected, $result);
    }
}
