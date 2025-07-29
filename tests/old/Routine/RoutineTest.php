<?php

namespace App\Tests;

use App\Repository\ConditionRoutineRepository;
use App\Repository\RoutineRepository;
use App\Repository\TaskRepository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use App\Repository\UserResponseRepository;
use App\Service\Clock\ClockInterface;
use App\Service\TestService;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutineTest extends WebTestCase
{
    private $userRepository;
    private $templateQuestionRepository;
    private $userResponseRepository;
    private $routineRepository;
    private $taskRepository;
    private $conditionRepository;

    private $client;
    private $container;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = static::getContainer();

        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->templateQuestionRepository = $this->client->getContainer()->get(TemplateQuestionRepository::class);
        $this->userResponseRepository = $this->client->getContainer()->get(UserResponseRepository::class);

        $this->routineRepository = $this->client->getContainer()->get(RoutineRepository::class);
        $this->taskRepository = $this->client->getContainer()->get(TaskRepository::class);
        $this->conditionRepository = $this->client->getContainer()->get(ConditionRoutineRepository::class);

        $this->removeAllRoutines();
        $this->removeAllTasks();
        $this->removeAllConditions();

        $this->removeAllUserResponse();

        $this->removeAllUsers();
        $this->createUsers();

        $this->removeQuestions();
        $this->createQuestions();

        // $this->staticDate();

    }

    private function staticDate()
    {
        $mockClock = new class implements ClockInterface {
            public function now(): DateTimeImmutable
            {
                return new \DateTimeImmutable("2024-01-01");
            }
        };
        
        $this->container->set(ClockInterface::class, $mockClock);
    }

    private function removeAllConditions()
    {
        $routines = $this->conditionRepository->findAll();
        foreach ($routines as $routine) {
            $this->conditionRepository->remove($routine, true);
        }
    }

    private function removeAllRoutines()
    {
        $conditions = $this->routineRepository->findAll();
        foreach ($conditions as $condition) {
            $this->routineRepository->remove($condition, true);
        }
    }
    private function removeAllTasks()
    {
        $tasks = $this->taskRepository->findAll();
        foreach ($tasks as $task) {
            $this->taskRepository->remove($task, true);
        }
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

    public function testGetRoutinesByUser()
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


        // create condition 
        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "Yes",
            "description" => "J ai repondu oui",
            "time" => "14:30:00",
            "question" => $firstQuestion->id,
            "response" => "YES"
        ]));
        
        $this->client->request('POST', '/condition-routine/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            "name" => "No",
            "description" => "J ai repondu non",
            "time" => "14:30:00",
            "question" => $firstQuestion->id,
            "response" => "NO"
        ]));
        
        $this->client->request('POST', '/user-response/new/' . $firstQuestion->id, [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'response' => 'NO',
        ]));

        // test not authorized
        $this->client->request('GET', '/routine/get-by-user', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token . "a",
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());

        // test authorized
        $this->client->request('GET', '/routine/get-by-user', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        

        $responseContent = json_decode($this->client->getResponse()->getContent(), true)[0];
        $this->assertEquals("No", $responseContent['name']);
        $this->assertEquals("J ai repondu non", $responseContent['description']);
        $this->assertEquals("1970-01-01T14:30:00+00:00", $responseContent['taskTime']);
    }

}