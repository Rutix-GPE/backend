<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        // on récupère le UserRepository depuis le container de test
        $this->userRepository = $this->client
            ->getContainer()
            ->get(UserRepository::class);

        // on vide la table user pour repartir d’un état propre
        foreach ($this->userRepository->findAll() as $user) {
            $this->userRepository->remove($user, true);
        }
    }

    public function testRegister(): void
    {
        $this->client->request(
            'POST',
            '/user/register',
            [], [], 
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username'  => 'testuser',
                'firstname' => 'John',
                'lastname'  => 'Doe',
                'email'     => 'john.doe@example.com',
                'password'  => 'password123'
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testAuthenticate(): void
    {
        // on recrée un utilisateur
        $this->client->request(
            'POST',
            '/user/register',
            [], [], 
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username'  => 'testuser1',
                'firstname' => 'John',
                'lastname'  => 'Doe',
                'email'     => 'john.doe1@example.com',
                'password'  => 'password123'
            ])
        );

        // puis on l’authentifie
        $this->client->request(
            'POST',
            '/user/authenticate',
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'testuser1',
                'password' => 'password123'
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data, "La réponse doit contenir un token JWT");
        $this->assertArrayHasKey('user', $data,  "La réponse doit contenir l'objet user");
        $this->assertSame('testuser1', $data['user']['username']);
    }
}
