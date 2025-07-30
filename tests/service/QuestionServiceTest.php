<?php

namespace App\Tests\Service;

use App\Service\QuestionService;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use App\Entity\Question;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class QuestionServiceTest extends TestCase
{
    private $questionRepository;
    private $userRepository;
    private $service;

    protected function setUp(): void
    {
        $this->questionRepository = $this->createMock(QuestionRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->service = new QuestionService(
            $this->questionRepository,
            $this->userRepository
        );
    }

    public function testShowReturnsQuestion()
    {
        $question = new Question();
        $this->questionRepository->method('findOneBy')->with(['id' => 42])->willReturn($question);

        $result = $this->service->show(42);

        $this->assertSame($question, $result);
    }

    public function testListReturnsAllQuestions()
    {
        $questions = [new Question(), new Question()];
        $this->questionRepository->method('findAll')->willReturn($questions);

        $result = $this->service->list();

        $this->assertSame($questions, $result);
    }

    public function testListRootReturnsRootQuestions()
    {
        $questions = [new Question(), new Question()];
        $this->questionRepository->method('findBy')->with(['isRootQuestion' => true])->willReturn($questions);

        $result = $this->service->listRoot();

        $this->assertSame($questions, $result);
    }

    public function testNewReturnsErrorOnInvalidData()
    {
        $result = $this->service->new(null, 'desc', true, false);
        $this->assertSame(-1, $result);

        $result = $this->service->new('name', null, true, false);
        $this->assertSame(-1, $result);

        $result = $this->service->new('name', 'desc', 'not_bool', false);
        $this->assertSame(-1, $result);
    }

    public function testNewReturnsConflictIfNameExists()
    {
        $existing = new Question();
        $this->questionRepository->method('findOneBy')->with(['name' => 'toto'])->willReturn($existing);

        $result = $this->service->new('toto', 'desc', true, false);
        $this->assertSame([$existing, Response::HTTP_CONFLICT], $result);
    }

    public function testNewCreatesQuestionOnValidData()
    {
        $this->questionRepository->method('findOneBy')->willReturn(null);
        $this->questionRepository->expects($this->once())->method('add')->with($this->isInstanceOf(Question::class), true);

        $result = $this->service->new('nouvelle', 'desc', true, false);
        $this->assertInstanceOf(Question::class, $result[0]);
        $this->assertSame(Response::HTTP_CREATED, $result[1]);
    }

    public function testEditUpdatesQuestionData()
    {
        $question = new Question();
        $question->setContent('old');
        $question->setIsRootQuestion(false);
        $question->setIsQuickQuestion(false);

        $this->questionRepository->method('findOneBy')->willReturn($question);
        $this->questionRepository->expects($this->once())->method('add')->with($question, true);

        $result = $this->service->edit(2, ['content' => 'new', 'rootQuestion' => true]);

        $this->assertSame($question, $result[0]);
        $this->assertSame('new', $question->getContent());
        $this->assertTrue($question->isRootQuestion());
        $this->assertTrue($question->isQuickQuestion());
        $this->assertSame(Response::HTTP_OK, $result[1]);
    }

    public function testSetNextRootQuestionSetsUserField()
    {
        $root1 = new Question(); $root1->setIsRootQuestion(true); $root1->setName('q1');
        $root2 = new Question(); $root2->setIsRootQuestion(true); $root2->setName('q2');
        $root1->setContent('c1'); $root2->setContent('c2');

        // Donne un id à chaque question
        $refProp = function ($o, $v) { $prop = (new \ReflectionClass($o))->getProperty('id'); $prop->setAccessible(true); $prop->setValue($o, $v); };
        $refProp($root1, 1);
        $refProp($root2, 2);

        $all = [$root1, $root2];
        $this->questionRepository->method('findBy')->with(['isRootQuestion' => true])->willReturn($all);

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('setNextRootQuestion')->with($root2);
        $this->userRepository->expects($this->once())->method('add')->with($user, true);

        $this->service->setNextRootQuestion($root1, $user);
    }
}

