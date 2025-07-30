<?php

namespace App\Tests\Service;

use App\Service\RoutineService;
use App\Entity\Routine;
use App\Repository\RoutineRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class RoutineServiceTest extends TestCase
{
    private $routineRepository;
    private $service;

    protected function setUp(): void
    {
        $this->routineRepository = $this->createMock(RoutineRepository::class);
        $this->service = new RoutineService($this->routineRepository);
    }

    public function testShowReturnsRoutineById()
    {
        $routine = new Routine();
        $this->routineRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 42])
            ->willReturn($routine);

        $result = $this->service->show(42);
        $this->assertSame($routine, $result);
    }

    public function testListReturnsAllRoutines()
    {
        $routines = [new Routine(), new Routine()];
        $this->routineRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($routines);

        $result = $this->service->list();
        $this->assertSame($routines, $result);
    }

    public function testNewCreatesRoutine()
    {
        $this->routineRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Test Routine'])
            ->willReturn(null);

        $this->routineRepository->expects($this->once())
            ->method('add');

        $routineTime = new \DateTime('09:00');
        $days = [1, 2, 3];
        [$routine, $code] = $this->service->new('Test Routine', 'Desc', $days, $routineTime);

        $this->assertInstanceOf(Routine::class, $routine);
        $this->assertSame(Response::HTTP_CREATED, $code);
        $this->assertSame('Test Routine', $routine->getName());
        $this->assertSame('Desc', $routine->getDescription());
        $this->assertSame($days, $routine->getDays());
        $this->assertEquals($routineTime, $routine->getTaskTime());
    }

    public function testNewReturnsConflictIfNameExists()
    {
        $existing = new Routine();
        $this->routineRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Test Routine'])
            ->willReturn($existing);

        [$routine, $code] = $this->service->new('Test Routine', 'Desc', [1], new \DateTime());

        $this->assertSame($existing, $routine);
        $this->assertSame(Response::HTTP_CONFLICT, $code);
    }

    public function testNewReturnsMinusOneIfInvalidArguments()
    {
        $result = $this->service->new([], 'desc', [1], new \DateTime());
        $this->assertSame(-1, $result);

        $result = $this->service->new('Test', [], [1], new \DateTime());
        $this->assertSame(-1, $result);

        $result = $this->service->new('Test', 'desc', 'notarray', new \DateTime());
        $this->assertSame(-1, $result);

        $result = $this->service->new('Test', 'desc', [1], 'notadate');
        $this->assertSame(-1, $result);
    }

    public function testEditUpdatesRoutine()
    {
        $routine = new Routine();
        $routine->setDescription('old');
        $routine->setDays([1]);
        $routine->setTaskTime(new \DateTime('08:00'));

        $this->routineRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 7])
            ->willReturn($routine);

        $this->routineRepository->expects($this->once())
            ->method('add')
            ->with($routine, true);

        $data = [
            'description' => 'new desc',
            'days' => [2,3],
            'taskTime' => new \DateTime('10:00')
        ];

        [$edited, $code] = $this->service->edit(7, $data);

        $this->assertSame($routine, $edited);
        $this->assertSame('new desc', $routine->getDescription());
        $this->assertSame([2,3], $routine->getDays());
        $this->assertEquals(new \DateTime('10:00'), $routine->getTaskTime());
        $this->assertSame(Response::HTTP_OK, $code);
    }
}
