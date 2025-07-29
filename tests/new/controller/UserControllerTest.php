<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);

        // On supprime tous les users avant chaque test pour un état propre
        foreach ($this->userRepository->findAll() as $user) {
            $this->userRepository->remove($user, true);
        }
    }

    private function createUser(string $username, string $email, array $roles = ['ROLE_USER']): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setEmail($email);
        $user->setPassword('hashedpassword'); // pas grave si non-hashé pour ce test
        $user->setRoles($roles);

        $this->userRepository->add($user, true);
        return $user;
    }

    public function testListUserAndShowUser(): void
    {
        $user1 = $this->createUser('user1', 'user1@example.com');
        $user2 = $this->createUser('user2', 'user2@example.com', ['ROLE_ADMIN']);

        // 1. test /user/list
        $this->client->request('GET', '/user/list');
        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(2, $data);

        // Chaque user doit apparaître dans la liste
        $usernames = array_column($data, 'username');
        $this->assertContains('user1', $usernames);
        $this->assertContains('user2', $usernames);

        // 2. test /user/show/{id} OK
        $this->client->request('GET', '/user/show/' . $user1->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('user1', $data['username']);

        // 3. test /user/show/{id} 404
        $this->client->request('GET', '/user/show/99999');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
}
