<?php

namespace App\Tests;

use App\Repository\UserRepository;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserTest extends WebTestCase
{

    private $userRepository;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->removeAllUsers();
        $this->createUsers();
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

    private function getFirstUser()
    {
        $users = $this->userRepository->findAll();
        return $users[0];
    }

    public function testShowUser()
    {
        $firstUser = $this->getFirstUser();

        
        // test first user
        $this->client->request('GET', '/user/show/'.$firstUser->id, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
                
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("jhon_doe", $responseContent['username']);
        $this->assertEquals("John", $responseContent['firstname']);
        $this->assertEquals("Doe", $responseContent['lastname']);
        $this->assertEquals("john.doe@example.com", $responseContent['email']);


        // test not found user
        $this->client->request('GET', '/user/show/'.$firstUser->id + 5, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

    }

    public function testListUser()
    {        
        // test all user
        $this->client->request('GET', '/user/list', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
                
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $userOne = $responseContent[0];
        $userTwo = $responseContent[1];

        $this->assertEquals("jhon_doe", $userOne['username']);
        $this->assertEquals("John", $userOne['firstname']);
        $this->assertEquals("john.doe@example.com", $userOne['email']);

        $this->assertEquals("alice_jhonson", $userTwo['username']);
        $this->assertEquals("Alice", $userTwo['firstname']);
        $this->assertEquals("alice.johnson@example.com", $userTwo['email']);


        // test not found user
        $this->removeAllUsers();
        $this->client->request('GET', '/user/list', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateUser()
    {
        $firstUser = $this->getFirstUser();

        // test first user
        $this->client->request('PUT', '/user/update/'.$firstUser->id, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'jhon_doeV2',
            'firstname' => 'JohnV2',
            'lastname' => 'DoeV2',
            'email' => 'john.doe@example.comV2'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("jhon_doeV2", $responseContent['username']);
        $this->assertEquals("JohnV2", $responseContent['firstname']);
        $this->assertEquals("DoeV2", $responseContent['lastname']);
        $this->assertEquals("john.doe@example.comV2", $responseContent['email']);

        $this->client->request('PUT', '/user/update/'.$firstUser->id  + 5, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'jhon_doeV2',
            'firstname' => 'JohnV2',
            'lastname' => 'DoeV2',
            'email' => 'john.doe@example.comV2'
        ]));


        // not found user
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

    }

    public function testUpdateUserRole()
    {
        $firstUser = $this->getFirstUser();


        // test first user - admin role
        $this->client->request('PUT', '/user/update-role/'.$firstUser->id, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'role' => 'admin'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['ROLE_ADMIN'], $responseContent['roles']);
        

        // test first user - user role
        $this->client->request('PUT', '/user/update-role/'.$firstUser->id, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'role' => 'user'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(['ROLE_USER'], $responseContent['roles']);


        // test first user - not found role
        $this->client->request('PUT', '/user/update-role/'.$firstUser->id, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'role' => 'hacker'
        ]));

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteUser()
    {
        $firstUser = $this->getFirstUser();


        // test not found user
        $this->client->request('DELETE', '/user/delete/'.$firstUser->id + 5, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());


        // test first user
        $this->client->request('DELETE', '/user/delete/'.$firstUser->id, [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
    }

    public function testMe()
    {
        $client = $this->client;


        $firstUser = $this->getFirstUser();

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $firstUser->username,
            'password' => 'newpassword123'
        ]));

        $this->assertResponseIsSuccessful();

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $responseContent);

        $jwt = $responseContent['token'];

        // test avec token
        $this->client->request('GET', '/user/me', [], [], [
             'HTTP_AUTHORIZATION' => 'Bearer ' . $jwt,
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $profile = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('jhon_doe', $profile['username']);
        $this->assertEquals('John', $profile['firstname']);
        $this->assertEquals('Doe', $profile['lastname']);
        $this->assertEquals('john.doe@example.com', $profile['email']);


        // test sans token
        $this->client->request('GET', '/user/me', [], [], []);

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());

    }

    public function testUpdateMemo()
    {
        $client = $this->client;


        $firstUser = $this->getFirstUser();

        $this->client->request('POST', '/user/authenticate', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $firstUser->username,
            'password' => 'newpassword123'
        ]));


        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseContent);

        // test avec mémo
        $this->client->request('PUT', '/user/update-memo', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $responseContent['token'],
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'memo' => 'Nouvelle valeur du mémo',
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());


        // test sans mémo
        $this->client->request('PUT', '/user/update-memo', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $responseContent['token'],
            'CONTENT_TYPE' => 'application/json']);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

    }

}