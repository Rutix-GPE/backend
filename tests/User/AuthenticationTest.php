<?php

namespace App\Tests;

use App\Repository\UserRepository;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationTest extends WebTestCase
{

    private $userRepository;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->removeAllUsers();
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
        // test missing informations
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
        ]));

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());


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

        
        // test duplicate
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ]));

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());

    }

    public function testAuthenticate()
    {
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ]));


        // test by username
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'password' => 'password123'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsString($responseContent['token']);

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'wrong',
            'password' => 'password123'
        ]));

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());


        // test by email
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsString($responseContent['token']);
    
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'wrong',
            'password' => 'password123'
        ]));

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());


        // test missing informations
        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'password' => 'password123'
        ]));

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());

    }

}