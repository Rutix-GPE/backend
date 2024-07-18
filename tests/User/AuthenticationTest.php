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

    public function testRegisterMissingFields()
    {
        $json = [
            "username" => "testuser"
        ];
        $json = json_encode($json);

        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], $json);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $responseContent);
        $this->assertEquals('Missing informations', $responseContent['error']);
    }

    public function testRegisterSuccess()
    {
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testuser',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'phonenumber' => '123456789',
            'country' => 'France',
            'postalcode' => '75000',
            'city' => 'Paris',
            'adress' => '123 Rue de Paris'
        ]));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $responseContent);
        $this->assertArrayHasKey('username', $responseContent);
        $this->assertEquals('testuser', $responseContent['username']);
    }

}
