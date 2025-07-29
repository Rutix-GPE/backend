<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProtectedControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);

        // Nettoyage users
        foreach ($this->userRepository->findAll() as $user) {
            $this->userRepository->remove($user, true);
        }
    }

    private function createAndAuthenticateUser($username, $email, $password = 'password123', $roles = ['ROLE_USER'])
    {
        // Création de l'utilisateur (même logique que ton UserService)
        $user = new User();
        $user->setUsername($username);
        $user->setFirstname('Test');
        $user->setLastname('User');
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); // Simule un mot de passe hashé
        $user->setRoles($roles);
        $this->userRepository->add($user, true);

        // Authentification pour obtenir le token JWT
        $this->client->request(
            'POST',
            '/user/authenticate',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => $username, 'password' => $password])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        return ['user' => $user, 'token' => $data['token']];
    }

    public function testUpdateUser(): void
    {
        $userInfo = $this->createAndAuthenticateUser('updateuser', 'updateuser@test.com');
        $user = $userInfo['user'];
        $token = $userInfo['token'];

        $payload = [
            'username'  => 'updated',
            'firstname' => 'Jane',
            'lastname'  => 'Doe',
            'email'     => 'updated@test.com'
        ];

        $this->client->request(
            'PUT',
            '/user/update/' . $user->getId(),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode($payload)
        );

        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('updated', $responseData['username']);
        $this->assertSame('updated@test.com', $responseData['email']);
    }

    public function testUpdateRole(): void
    {
        // Crée un user admin
        $adminInfo = $this->createAndAuthenticateUser('adminuser', 'admin@test.com', 'adminpass', ['ROLE_ADMIN']);
        $admin = $adminInfo['user'];
        $token = $adminInfo['token'];

        // Crée un user à modifier
        $user = $this->createAndAuthenticateUser('basicuser', 'basic@test.com')['user'];

        $this->client->request(
            'PUT',
            '/user/update-role/' . $user->getId(),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode(['role' => 'admin'])
        );

        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(['ROLE_ADMIN'], $responseData['roles']);
    }

    public function testUpdateMemo(): void
    {
        $userInfo = $this->createAndAuthenticateUser('memouser', 'memo@test.com');
        $token = $userInfo['token'];

        $this->client->request(
            'PUT',
            '/user/update-memo',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode(['memo' => 'Ceci est mon memo'])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('Ceci est mon memo', $data['Memo'] ?? $data['memo']);
    }

    public function testUpdateAvatar(): void
    {
        $userInfo = $this->createAndAuthenticateUser('avataruser', 'avatar@test.com');
        $token = $userInfo['token'];

        // Utilise un nom de fichier qui existe dans /public/img/avatar
        $avatarFilename = 'avatar_par_defaut.png';

        $this->client->request(
            'PATCH',
            '/user/update-avatar',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode(['avatar' => $avatarFilename])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($avatarFilename, $data['avatarFile'] ?? $data['avatar_file']);
    }

    public function testMe(): void
    {
        $userInfo = $this->createAndAuthenticateUser('meuser', 'meuser@test.com');
        $token = $userInfo['token'];

        $this->client->request(
            'GET',
            '/user/me',
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer ' . $token
            ]
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('meuser', $data['username']);
    }

    public function testDeleteUser(): void
    {
        $adminInfo = $this->createAndAuthenticateUser('deladmin', 'deladmin@test.com', 'adminpass', ['ROLE_ADMIN']);
        $adminToken = $adminInfo['token'];

        $user = $this->createAndAuthenticateUser('tobedeleted', 'tobedeleted@test.com')['user'];

        $this->client->request(
            'DELETE',
            '/user/delete/' . $user->getId(),
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer ' . $adminToken
            ]
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($data['success']);
    }
}
