<?php

namespace App\Tests;

use App\Repository\ConditionRoutineRepository;
use App\Repository\RoutineRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\UserResponseRepository;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use App\Dto\Auth\UserLoginDTO;
class AuthenticationTest extends WebTestCase
{

    private $userRepository;
    private $userResponseRepository;
    private $conditionRepository;

    private $routineRepository;
    private $taskRepository;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->userResponseRepository = $this->client->getContainer()->get(UserResponseRepository::class);
        $this->conditionRepository = $this->client->getContainer()->get(ConditionRoutineRepository::class);

        $this->routineRepository = $this->client->getContainer()->get(RoutineRepository::class);
        $this->taskRepository = $this->client->getContainer()->get(TaskRepository::class);

        $this->removeAllRoutines();
        $this->removeAllTasks();


        $this->removeAllUserResponse();
        $this->removeAllUsers();


        
    }

    private function removeAllRoutines()
    {
        $routines = $this->routineRepository->findAll();
        foreach ($routines as $routine) {
            $this->routineRepository->remove($routine, true);
        }
    }
    private function removeAllTasks()
    {
        $tasks = $this->taskRepository->findAll();
        foreach ($tasks as $task) {
            $this->taskRepository->remove($task, true);
        }
    }

    private function removeAllConditions()
    {
        $conditions = $this->conditionRepository->findAll();
        foreach ($conditions as $condition) {
            $this->conditionRepository->remove($condition, true);
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

    public function testRegister()
    {
        // test missing password
        /*
       // test missing informations
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
        ]));
        $response = $this->client->getResponse();
            $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
            $this->assertJson($this->client->getResponse()->getContent());
*/

        // test create user
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ]));
       $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
/*
        
        // test duplicate
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ]));

        $this->assertEquals(409, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
*/
    }

    public function testAuthenticate()
    {
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser1',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe1@example.com',
            'password' => 'password123'
        ]));


        // test by username 
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser1',
            'password' => 'password123'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsString($responseContent['token']);

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'wrong',
            'password' => 'password123'
        ]));

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());


        // test by email
        
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'john.doe1@example.com',
            'password' => 'password123'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsString($responseContent['token']);
    

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'wrong',
            'password' => 'password123'
        ]));
        $response = $this->client->getResponse();
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());


        // test missing informations
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'password' => 'password123'
        ]));

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        
    }
    
}