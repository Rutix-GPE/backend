<?php

namespace App\Tests\Service;

use App\Service\UserResponseService;
use App\Service\RelationService;
use App\Service\QuestionService;
use App\Service\UserRoutineService;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\Routine;
use App\Entity\Relation;
use App\Repository\RelationRepository;
use App\Repository\QuestionRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UserResponseServiceTest extends TestCase
{
    private $relationRepository;
    private $relationService;
    private $questionRepository;
    private $questionService;
    private $userRoutineService;
    private $service;

    protected function setUp(): void
    {
        $this->relationRepository = $this->createMock(RelationRepository::class);
        $this->relationService = $this->createMock(RelationService::class);
        $this->questionRepository = $this->createMock(QuestionRepository::class);
        $this->questionService = $this->createMock(QuestionService::class);
        $this->userRoutineService = $this->createMock(UserRoutineService::class);

        $this->service = new UserResponseService(
            $this->relationRepository,
            $this->relationService,
            $this->questionRepository,
            $this->questionService,
            $this->userRoutineService
        );
    }

    public function testGetFirstQuestionReturnsArray()
    {
        $user = $this->createMock(User::class);
        $question = $this->createMock(Question::class);
        $question->method('getId')->willReturn(1);
        $question->method('getContent')->willReturn('Contenu de la question');
        $answers = ['Oui', 'Non'];

        $this->questionRepository->method('findOneBy')->with(['id' => 1])->willReturn($question);
        $this->relationService->method('getAnswer')->with(1)->willReturn($answers);

        // On veut vérifier que setNextRootQuestion est bien appelée
        $this->questionService
            ->expects($this->once())
            ->method('setNextRootQuestion')
            ->with($question, $user);

        $result = $this->service->getFirstQuestion($user);

        $this->assertEquals([
            'question' => 'Contenu de la question',
            'answer' => $answers,
            'url' => 'user-response/next-question/1'
        ], $result);
    }

    public function testGetNextQuestionWithTargetQuestion()
    {
        $user = $this->createMock(User::class);

        // Setup Relation
        $relation = $this->createMock(Relation::class);
        $nextQuestion = $this->createMock(Question::class);
        $nextQuestion->method('getId')->willReturn(99);
        $nextQuestion->method('getContent')->willReturn('La suite ?');

        $relation->method('getTargetQuestion')->willReturn($nextQuestion);
        $relation->method('getTargetRoutine')->willReturn(null);

        $this->relationService->method('getQuestionByIdAndAnswer')->willReturn($relation);
        $this->relationService->method('getAnswer')->with(99)->willReturn(['Oui', 'Non']);

        $this->questionService
            ->expects($this->once())
            ->method('setNextRootQuestion')
            ->with($nextQuestion, $user);

        $result = $this->service->getNextQuestion(2, 'oui', $user);

        $this->assertEquals([
            'code' => Response::HTTP_OK,
            'question' => 'La suite ?',
            'answer' => ['Oui', 'Non'],
            'url' => 'user-response/next-question/99'
        ], $result);
    }

    public function testGetNextQuestionWithTargetRoutine()
    {
        $user = $this->createMock(User::class);
        $routine = $this->createMock(Routine::class);
        $nextRootQuestion = $this->createMock(Question::class);

        $relation = $this->createMock(Relation::class);
        $relation->method('getTargetQuestion')->willReturn(null);
        $relation->method('getTargetRoutine')->willReturn($routine);

        $user->method('getNextRootQuestion')->willReturn($nextRootQuestion);
        $nextRootQuestion->method('getId')->willReturn(3);
        $nextRootQuestion->method('getContent')->willReturn('Suite après routine');

        $this->relationService->method('getQuestionByIdAndAnswer')->willReturn($relation);

        $this->userRoutineService->method('copyRoutine')->willReturn('Routine copiée');
        $this->relationService->method('getAnswer')->with(3)->willReturn(['A', 'B']);

        $this->questionService
            ->expects($this->once())
            ->method('setNextRootQuestion')
            ->with($nextRootQuestion, $user);

        $result = $this->service->getNextQuestion(1, 'X', $user);

        $this->assertEquals([
            'code' => 'Routine copiée',
            'question' => 'Suite après routine',
            'answer' => ['A', 'B'],
            'url' => 'user-response/next-question/3'
        ], $result);
    }

    public function testGetNextQuestionNoNextQuestion()
    {
        $user = $this->createMock(User::class);
        $relation = $this->createMock(Relation::class);

        $relation->method('getTargetQuestion')->willReturn(null);
        $relation->method('getTargetRoutine')->willReturn(null);
        $user->method('getNextRootQuestion')->willReturn(null);

        $this->relationService->method('getQuestionByIdAndAnswer')->willReturn($relation);

        $this->questionService
            ->expects($this->once())
            ->method('setNextRootQuestion')
            ->with(null, $user);

        $result = $this->service->getNextQuestion(5, 'nope', $user);

        $this->assertEquals([
            'code' => Response::HTTP_OK,
            'question' => null,
            'answer' => null,
            'url' => null,
        ], $result);
    }
}
