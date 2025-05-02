<?php

namespace App\Tests;

use App\Repository\RoutineRepository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use App\Repository\UserResponseRepository;
use App\Service\TestService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserResponseTest extends WebTestCase
{
    
    private $userRepository;
    private $templateQuestionRepository;
    private $userResponseRepository;
    private $routineRepository;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->templateQuestionRepository = $this->client->getContainer()->get(TemplateQuestionRepository::class);
        $this->userResponseRepository = $this->client->getContainer()->get(UserResponseRepository::class);

        $this->routineRepository = $this->client->getContainer()->get(RoutineRepository::class);


        $this->removeAllUserResponse();

        $this->removeAllUsers();
        $this->createUsers();

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

    private function removeAllUsers()
    {
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $this->userRepository->remove($user, true);
        }
    }

    private function createUsers()
    {
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'jhon_doe',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'newpassword123'
        ]));

        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'alice_jhonson',
            'firstname' => 'Alice',
            'lastname' => 'Johnson',
            'email' => 'alice.johnson@example.com',
            'password' => 'newpassword123'
        ]));
        
    }

    private function getHeaderApiCall()
    {
        return [
            'CONTENT_TYPE' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getFirstUserToken()
        ];
    }

    private function getFirstUser()
    {
        $users = $this->userRepository->findAll();
        return $users[0];
    }

    private function getFirstUserToken()
    {
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'john.doe@example.com',
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        // dd($responseContent);

        return $responseContent['token'];
    }

    private function removeQuestions()
    {
        $users = $this->templateQuestionRepository->findAll();
        foreach ($users as $user) {
            $this->templateQuestionRepository->remove($user, true);
        }
    }

    private function createQuestions()
    {
        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'test_one',
            'content' => 'First test',
            "type" => "multiple_choice",
            "choice" => ["YES", "NO"]
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

    public function testNewUserQuestion()
    {
        $firstUser = $this->getFirstUser();
        $firstQuestion = $this->getFirstQuestion();

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $firstUser->username,
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        $token = $responseContent['token'];


        // test not found
        $this->client->request('POST', '/user-response/new/' . $firstQuestion->id + 5, [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'response' => 'Nouvelle valeur du mémo',
        ]));

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());


        // test create 
        $this->client->request('POST', '/user-response/new/' . $firstQuestion->id, [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'response' => 'Nouvelle valeur du mémo',
        ]));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("test_one", $responseContent['name']);
        $this->assertEquals("First test", $responseContent['content']);
        $this->assertEquals("multiple_choice", $responseContent['type']);
        $this->assertEquals(["YES", "NO"], $responseContent['choice']);
        $this->assertEquals(1, $responseContent['page']);
        $this->assertEquals("Nouvelle valeur du mémo", $responseContent['response']);
        $this->assertEquals("/api/users/".$firstUser->id, $responseContent['user']);
        $this->assertEquals("/api/template_questions/".$firstQuestion->id, $responseContent['question']);



        // test duplicate
        $this->client->request('POST', '/user-response/new/' . $firstQuestion->id, [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'response' => 'Nouvelle valeur du mémo',
        ]));

        $this->assertEquals(409, $this->client->getResponse()->getStatusCode());

        // ** condition create routine ** //

        $this->client->request('POST', '/template-question/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "Type de repas préféré",
            "content" => "Quel type de repas préférez-vous pour vos routines alimentaires ?",
            "type" => "multiple_choice",
            "choice" => ["Petit-déjeuner léger","Déjeuner équilibré","Diner copieux","Collation saine"]
        ]));
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        


        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "Petit-déjeuner léger",
            "description" => "Je prend un petit-déjeuner léger",
            "time" => "14:30:00",
            "question" => $responseContent['id'],
            "response" => "Petit-déjeuner léger"
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        
        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "Déjeuner équilibré",
            "description" => "Je prend un déjeuner équilibré",
            "time" => "12:30:00",
            "question" => $responseContent['id'],
            "response" => "Déjeuner équilibré"
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        

        $this->client->request('POST', '/user-response/new/' . $responseContent['id'], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'response' => 'Déjeuner équilibré',
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());


        // test created routine
        $routine = $this->routineRepository->findBy([
            "User" => $firstUser->id,
            "name" => "Déjeuner équilibré"
        ]);

        $date = new \DateTime("1970-01-01, 12:30");

        $this->assertEquals(1, sizeof($routine));
        $this->assertEquals("Déjeuner équilibré", $routine[0]->getName());
        $this->assertEquals("Je prend un déjeuner équilibré", $routine[0]->getDescription());
        $this->assertEquals($date, $routine[0]->getTaskTime());
        
        // test created tasks
        
    }

}