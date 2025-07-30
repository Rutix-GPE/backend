<?php

namespace App\Tests\Service;

use App\Service\RelationService;
use App\Entity\Relation;
use App\Entity\Question;
use App\Entity\Routine;
use App\Repository\RelationRepository;
use App\Repository\RoutineRepository;
use App\Repository\QuestionRepository;
use PHPUnit\Framework\TestCase;

class RelationServiceTest extends TestCase
{
    private $relationRepository;
    private $routineRepository;
    private $questionRepository;
    private $service;

    protected function setUp(): void
    {
        $this->relationRepository = $this->createMock(RelationRepository::class);
        $this->routineRepository = $this->createMock(RoutineRepository::class);
        $this->questionRepository = $this->createMock(QuestionRepository::class);

        $this->service = new RelationService(
            $this->relationRepository,
            $this->routineRepository,
            $this->questionRepository
        );
    }

    public function testJoinWithTargetQuestion()
    {
        $source = new Question();
        $target = new Question();

        $this->questionRepository->method('findOneBy')
            ->willReturnMap([
                [['name' => 'source'], null, $source],
                [['name' => 'target'], null, $target],
            ]);

        $this->relationRepository->expects($this->once())->method('add')->with($this->isInstanceOf(Relation::class), true);

        $relation = $this->service->join('source', 'target', 'question', 'myAnswer');
        $this->assertInstanceOf(Relation::class, $relation);
        $this->assertSame($source, $relation->getSource());
        $this->assertSame($target, $relation->getTargetQuestion());
        $this->assertSame('myAnswer', $relation->getAnswer());
    }

    public function testJoinWithTargetRoutine()
    {
        $source = new Question();
        $targetRoutine = new Routine();

        $this->questionRepository->method('findOneBy')->willReturn($source);
        $this->routineRepository->method('findOneBy')->willReturn($targetRoutine);

        $this->relationRepository->expects($this->once())->method('add')->with($this->isInstanceOf(Relation::class), true);

        $relation = $this->service->join('source', 'routineName', 'routine', 'ans');
        $this->assertInstanceOf(Relation::class, $relation);
        $this->assertSame($source, $relation->getSource());
        $this->assertSame($targetRoutine, $relation->getTargetRoutine());
        $this->assertSame('ans', $relation->getAnswer());
    }

    public function testGetSourceReturnsRelations()
    {
        $question = new Question();
        $found = [new Relation(), new Relation()];
        $this->questionRepository->method('findOneBy')->with(['id' => 5])->willReturn($question);
        $this->relationRepository->method('findBy')->with(['source' => $question])->willReturn($found);

        $result = $this->service->getSource(5);
        $this->assertSame($found, $result);
    }

    public function testGetTargetQuestionReturnsRelations()
    {
        $question = new Question();
        $found = [new Relation()];
        $this->questionRepository->method('findOneBy')->with(['id' => 3])->willReturn($question);
        $this->relationRepository->method('findBy')->with(['targetQuestion' => $question])->willReturn($found);

        $result = $this->service->getTargetQuestion(3);
        $this->assertSame($found, $result);
    }

    public function testGetAnswerReturnsAnswers()
    {
        $question = new Question();
        $relation1 = $this->createMock(Relation::class);
        $relation2 = $this->createMock(Relation::class);
        $relation1->method('getAnswer')->willReturn('a1');
        $relation2->method('getAnswer')->willReturn('a2');

        $this->questionRepository->method('findOneBy')->with(['id' => 6])->willReturn($question);
        $this->relationRepository->method('findBy')->with(['source' => $question])->willReturn([$relation1, $relation2]);

        $result = $this->service->getAnswer(6);
        $this->assertEquals(['a1', 'a2'], $result);
    }

    public function testGetQuestionByIdAndAnswer()
    {
        $question = new Question();
        $relation = new Relation();

        $this->relationRepository->method('findOneBy')->with([
            'source' => $question,
            'answer' => 'answer1'
        ])->willReturn($relation);

        $result = $this->service->getQuestionByIdAndAnswer($question, 'answer1');
        $this->assertSame($relation, $result);
    }

    public function testGetRoutinByIdAndAnswer()
    {
        $question = new Question();
        $relation = new Relation();

        $this->relationRepository->method('findOneBy')->with([
            'source' => $question,
            'answer' => 'rout'
        ])->willReturn($relation);

        $result = $this->service->getRoutinByIdAndAnswer($question, 'rout');
        $this->assertSame($relation, $result);
    }
}

