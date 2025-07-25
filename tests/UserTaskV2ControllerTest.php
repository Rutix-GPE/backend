<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\UserTaskV2Repository;

class UserTaskV2ControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;
    private $userTaskV2Repository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->userTaskV2Repository = $this->client->getContainer()->get(UserTaskV2Repository::class);
        $this->removeAllUserTasks();
        $this->removeAllUsers();
        $this->createUsers();
    }

    private function removeAllUserTasks()
    {
        $tasks = $this->userTaskV2Repository->findAll();
        foreach ($tasks as $task) {
            $this->userTaskV2Repository->remove($task, true);
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

    private function authenticateUser($username, $password = 'newpassword123')
    {
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $username,
            'password' => $password
        ]));
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        return $responseContent['token'] ?? null;
    }

    public function testCreateTask()
    {
        $token = $this->authenticateUser('jhon_doe');
        $this->assertNotNull($token);

        $this->client->request('POST', '/user-task/v2/create', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'taskDateTime' => '2025-08-01T10:00:00'
        ]));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Test Task', $responseContent['name']);
        $this->assertEquals('Test Description', $responseContent['description']);
    }
}
