<?php

namespace App\Tests;

use App\Repository\ConditionRoutineRepository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use App\Repository\UserResponseRepository;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConditionRoutineTest extends WebTestCase
{
    
    private $templateQuestionRepository;
    private $conditionRoutineRepository;
    private $userResponseRepository;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->templateQuestionRepository = $this->client->getContainer()->get(TemplateQuestionRepository::class);
        $this->conditionRoutineRepository = $this->client->getContainer()->get(ConditionRoutineRepository::class);
        $this->userResponseRepository = $this->client->getContainer()->get(UserResponseRepository::class);

        // $this->removeAllUserResponse();
        $this->removeQuestions();
        $this->createQuestions();
    }

    private function removeAllUserResponse()
    {
        $userResponse = $this->userResponseRepository->findAll();
        foreach ($userResponse as $data) {
            $this->userResponseRepository->remove($data, true);
        }
    }

    private function removeQuestions()
    {
        $conditionRoutines = $this->conditionRoutineRepository->findAll();

        foreach ($conditionRoutines as $condition) {
            $this->conditionRoutineRepository->remove($condition, true);
        }

        $questions = $this->templateQuestionRepository->findAll();
        foreach ($questions as $question) {
            $this->templateQuestionRepository->remove($question, true);
        }
    }

    private function createQuestions()
    {
        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'TDAH',
            'content' => 'Est-ce que tu es TDAH ?',
            "type" => "multiple_choice",
            "choice" => ["YES", "NO"]
        ]));

        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'ANIMAL',
            'content' => 'As-tu un animal ?',
            "type" => "multiple_choice",
            "choice" => ["YES", "NO"]
        ]));
        
    }

    private function getFirstQuestion()
    {
        $questions = $this->templateQuestionRepository->findAll();
        return $questions[0];
    }

    public function testNew()
    {
        $firstQuestion = $this->getFirstQuestion();

        // test missing informations
        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            // "name" => "TDAH_YES",
            "description" => "Je suis TDAH",
            "time" => "14:30:00",
            "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "TDAH_YES",
            // "description" => "Je suis TDAH",
            "time" => "14:30:00",
            "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "TDAH_YES",
            "description" => "Je suis TDAH",
            // "time" => "14:30:00",
            "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "TDAH_YES",
            "description" => "Je suis TDAH",
            "time" => "14:30:00",
            // "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "TDAH_YES",
            "description" => "Je suis TDAH",
            "time" => "14:30:00",
            "question" => $firstQuestion->id,
            // "response" => "YES"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        // test created
        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "TDAH_YES",
            "description" => "Je suis TDAH",
            "time" => "14:30:00",
            "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("TDAH_YES", $responseContent['name']);
        $this->assertEquals("Je suis TDAH", $responseContent['description']);
        $this->assertEquals("2025-05-01T14:30:00+00:00", $responseContent['taskTime']);
        $this->assertEquals("/api/template_questions/".$firstQuestion->id, $responseContent['Question']);
        $this->assertEquals("YES", $responseContent['responseCondition']);
    }

    public function testGetQuestionResponse()
    {
        $firstQuestion = $this->getFirstQuestion();

        // test missing informations
        $this->client->request('GET', '/condition-routine/question_response', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            // "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/condition-routine/question_response', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "question" => $firstQuestion->id,
            // "response" => "YES"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());



        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "TDAH_YES",
            "description" => "Je suis TDAH",
            "time" => "14:30:00",
            "question" => $firstQuestion->id,
            "response" => "YES"
        ]));

        // test found
        $this->client->request('GET', '/condition-routine/question_response', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());


        // test not found
        $this->client->request('GET', '/condition-routine/question_response', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
        "question" => $firstQuestion->id + 1,
        "response" => "YES"
        ]));
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

    }
}