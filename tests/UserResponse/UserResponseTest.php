<?php

namespace App\Tests;

use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserResponseTest extends WebTestCase
{
    
    private $userRepository;

    private $templateQuestionRepository;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->templateQuestionRepository = $this->client->getContainer()->get(TemplateQuestionRepository::class);
        
        $this->removeAllUsers();
        $this->createUsers();

        $this->removeQuestions();
        $this->createQuestions();
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

    public function testDuplicate()
    {
        // dd($this->getHeaderApiCall());

        $this->client->request('POST', '/user-response/new/45', [], [], $this->getHeaderApiCall());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

}