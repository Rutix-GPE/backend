<?php

namespace App\Tests;

use App\Repository\ConditionRoutineRepository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TemplateQuestionTest 
{
    
    private $templateQuestionRepository;
    private $conditionRoutineRepository;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->templateQuestionRepository = $this->client->getContainer()->get(TemplateQuestionRepository::class);
        $this->conditionRoutineRepository = $this->client->getContainer()->get(ConditionRoutineRepository::class);

        $this->removeQuestions();
        $this->createQuestions();
    }

    private function removeQuestions()
    {
        // $users = $this->templateQuestionRepository->findAll();
        // foreach ($users as $user) {
        //     $this->templateQuestionRepository->remove($user, true);
        // }

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
            'name' => 'test_one',
            'content' => 'First test',
            'type' => 'text'
        ]));

        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'test_two',
            'content' => 'Second test',
            'type' => 'text',
            'page' => 45
        ]));
        
    }

    private function getFirstQuestion()
    {
        $questions = $this->templateQuestionRepository->findAll();
        return $questions[0];
    }

    public function testNew()
    {
        // test missing informations
        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "one",
            "content" => "First test",
            // "type" => "multiple_choice"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "one",
            // "content" => "First test",
            "type" => "multiple_choice"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            // "name" => "one",
            "content" => "First test",
            "type" => "multiple_choice"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        // test wrong type
        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "one",
            "content" => "First test",
            "type" => "wrong"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        // test without page (multiple_choice)
        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "one",
            "content" => "First test",
            "type" => "multiple_choice",
            "choice" => ["YES", "NO"]
        ]));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
                
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("one", $responseContent['name']);
        $this->assertEquals("First test", $responseContent['content']);
        $this->assertEquals("multiple_choice", $responseContent['type']);
        $this->assertEquals(1, $responseContent['page']);


        // test duplicate
        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'one',
            'content' => 'First test',
            'type' => 'text'
        ]));

        $this->assertEquals(409, $this->client->getResponse()->getStatusCode());
            
    }

    public function testShow()
    {
        $firstQuestion = $this->getFirstQuestion();


        // test first question
        $this->client->request('GET', '/template-question/show/'.$firstQuestion->id, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
                
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("test_one", $responseContent['name']);
        $this->assertEquals("First test", $responseContent['content']);
        $this->assertEquals("text", $responseContent['type']);
        $this->assertEquals(1, $responseContent['page']);

        // test not found question
        $this->client->request('GET', '/template-question/show/'.$firstQuestion->id + 5, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

    }

    public function testList()
    {                
        // test all user
        $this->client->request('GET', '/template-question/list', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
                
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $questionOne = $responseContent[0];
        $questionTwo = $responseContent[1];

        $this->assertEquals("test_one", $questionOne['name']);
        $this->assertEquals("First test", $questionOne['content']);
        $this->assertEquals("text", $questionOne['type']);
        $this->assertEquals(1, $questionOne['page']);

        $this->assertEquals("test_two", $questionTwo['name']);
        $this->assertEquals("Second test", $questionTwo['content']);
        $this->assertEquals("text", $questionTwo['type']);
        $this->assertEquals(45, $questionTwo['page']);


        // test not found user
        $this->removeQuestions();
        $this->client->request('GET', '/template-question/list', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testListPage()
    {        
        // test first page
        $this->client->request('GET', '/template-question/list/page/1', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        // dd($responseContent);
        $questionOne = $responseContent[0];

        $this->assertEquals("test_one", $questionOne['name']);
        $this->assertEquals("First test", $questionOne['content']);
        $this->assertEquals("text", $questionOne['type']);
        $this->assertEquals(1, $questionOne['page']);


        // test first page
        $this->client->request('GET', '/template-question/list/page/45', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());                
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $questionTwo = $responseContent[0];

        $this->assertEquals("test_two", $questionTwo['name']);
        $this->assertEquals("Second test", $questionTwo['content']);
        $this->assertEquals("text", $questionTwo['type']);
        $this->assertEquals(45, $questionTwo['page']);


        // test not found user
        $this->removeQuestions();
        $this->client->request('GET', '/template-question/list/page/78', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $firstUser = $this->getFirstQuestion();


        // test not found user
        $this->client->request('DELETE', '/template-question/delete/'.$firstUser->id + 5, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());


        // test first user
        $this->client->request('DELETE', '/template-question/delete/'.$firstUser->id, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
    }
}