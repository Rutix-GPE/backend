<?php

namespace App\Tests;

use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use App\Repository\UserResponseRepository;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserResponseTest extends WebTestCase
{
    
    private $userRepository;
    private $templateQuestionRepository;
    private $userResponseRepository;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->templateQuestionRepository = $this->client->getContainer()->get(TemplateQuestionRepository::class);
        $this->userResponseRepository = $this->client->getContainer()->get(UserResponseRepository::class);
        
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
            "name" => "Fréquence de l'exercice physique",
            "content" => "Combien de fois par semaine faites-vous de l'exercice physique ?",
            "type" => "multiple_choice",
            "choice" => ["1-2-3","4-5-6","7+"]
        ]));
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        


        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "TDAH_YES",
            "description" => "Je suis TDAH",
            "time" => "14:30:00",
            "question" => $responseContent['id'],
            "response" => "YES"
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());


        // TODO mettre à jour les template_question et condition_routine pour que des routines tasks soient crées 
        // TODO enlever removeAllUserResponse() si non utilisé dans les autre files de test

        // Tous les tests ne marche pas si ils sont lancé tous en même temps, voir comment faire pour résoudre
    }

}