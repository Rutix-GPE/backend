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
use Symfony\Component\Validator\Constraints\Date;

class TaskTest extends WebTestCase
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

    private function getSecondUser()
    {
        $users = $this->userRepository->findAll();
        return $users[1];
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

    public function testCreateTask()
    {
        $firstUser = $this->getFirstUser();

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $firstUser->username,
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        $token = $responseContent['token'];

        // test not authorized
        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token . 'a',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage",
            "description" => "Faire le ménage",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());


        // test missing informations
        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            // "name" => "Ménage",
            "description" => "Faire le ménage",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage",
            // "description" => "Faire le ménage",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage",
            "description" => "Faire le ménage",
            // "date" => "2025-05-10",
            "time" => "11:30"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage",
            "description" => "Faire le ménage",
            "date" => "2025-05-10",
            // "time" => "11:30"
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());


        // test created
        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage",
            "description" => "Faire le ménage",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $today = (new \DateTime())->format('Y-m-d');

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("Ménage", $responseContent['name']);
        $this->assertEquals("Faire le ménage", $responseContent['description']);
        $this->assertEquals("2025-05-10T00:00:00+00:00", $responseContent['taskDate']);
        $this->assertEquals($today."T11:30:00+00:00", $responseContent['taskTime']);

    }

    public function testUpdateTask()
    {
        $firstUser = $this->getFirstUser();

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $firstUser->username,
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        $token = $responseContent['token'];

        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage",
            "description" => "Faire le ménage",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        // test not authorized
        $this->client->request('PUT', '/task/update/'.$responseContent['id'], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token . 'a',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage V2",
            "time" => "11:30"
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());


        // test update
        $this->client->request('PUT', '/task/update/'.$responseContent['id'], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage V2",
            "time" => "11:30"
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $today = (new \DateTime())->format('Y-m-d');

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("Ménage V2", $responseContent['name']);
        $this->assertEquals($today."T11:30:00+00:00", $responseContent['taskTime']);


        $this->client->request('PUT', '/task/update/'.$responseContent['id'], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage V3",
            "time" => "15:45"
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("Ménage V3", $responseContent['name']);
        $this->assertEquals($today."T15:45:00+00:00", $responseContent['taskTime']);

    }

    public function testGetTaskByUser()
    {
        $firstUser = $this->getFirstUser();
        $secondUser = $this->getSecondUser();

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $firstUser->username,
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        $token1 = $responseContent['token'];

        // 

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $secondUser->username,
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        $token2 = $responseContent['token'];


        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token1,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage user one",
            "description" => "user one",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));

        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token2,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage user two",
            "description" => "user two",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));


        // test not authorized
        $this->client->request('GET', '/task/get-by-user', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token2 . 'a',
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());

        // test get by user
        $this->client->request('GET', '/task/get-by-user', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token2,
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true)[0];
        $this->assertEquals("Ménage user two", $responseContent['name']);
        $this->assertEquals("user two", $responseContent['description']);
        
    }

    public function testGetTaskByUserAndTime()
    {
        $firstUser = $this->getFirstUser();
        $secondUser = $this->getSecondUser();

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $firstUser->username,
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        $token1 = $responseContent['token'];

        // 

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $secondUser->username,
            'password' => 'newpassword123'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        $token2 = $responseContent['token'];


        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token1,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage user one",
            "description" => "user one",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));

        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token2,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage user two date",
            "description" => "user two",
            "date" => "2025-05-10",
            "time" => "11:30"
        ]));

        $this->client->request('POST', '/task/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token2,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "name" => "Ménage user two time",
            "description" => "user two",
            "date" => "2025-05-11",
            "time" => "15:45"
        ]));


        // test not authorized
        $this->client->request('GET', '/task/get-by-user-and-time/2025-05-10', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token2 . 'a',
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());


        // test get by user and date
        $this->client->request('GET', '/task/get-by-user-and-time/2025-05-10', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token2,
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true)[0];
        $this->assertEquals("Ménage user two date", $responseContent['name']);
    }

}
