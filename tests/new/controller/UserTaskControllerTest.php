<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\UserTask;
use App\Entity\Routine;
use App\Repository\UserRepository;
use App\Repository\UserTaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTaskControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;
    private $userTaskRepository;
    private $em;

    // public static function setUpBeforeClass(): void
    // {
    //     exec('php bin/console app:db:rebuild --env=test');
    // }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        $this->userTaskRepository = $container->get(UserTaskRepository::class);
        $this->em = $container->get('doctrine')->getManager();
    }

    private function createAndAuthenticateUser($username, $email, $password = 'password123')
    {
        $user = new User();
        $user->setUsername($username);
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

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

    public function testCreateAndGetUpdateDeleteTask(): void
    {
        // Authentification utilisateur
        $userInfo = $this->createAndAuthenticateUser('taskuser', 'taskuser@test.com');
        $token = $userInfo['token'];

        // Création de tâche
        $postData = [
            'name' => 'Ma première tâche',
            'description' => 'Une tâche test',
            'taskDateTime' => '2025-08-01T10:30:00+00:00'
        ];
        $this->client->request(
            'POST',
            '/user-task/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode($postData)
        );
        $this->assertResponseStatusCodeSame(201);
        $createdTask = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $createdTask);
        $this->assertEquals('Ma première tâche', $createdTask['name']);

        $taskId = $createdTask['id'];

        // Récupération des tâches de l'utilisateur
        $this->client->request(
            'GET',
            '/user-task/get-by-user',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );
        $this->assertResponseIsSuccessful();
        $tasks = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($tasks);
        $found = false;
        foreach ($tasks as $task) {
            if ($task['id'] == $taskId) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "La tâche créée doit être trouvée dans le listing.");

        // Mise à jour de la tâche
        $updateData = [
            'name' => 'Tâche modifiée',
            'description' => 'Description modifiée'
        ];
        $this->client->request(
            'PATCH',
            "/user-task/update/$taskId",
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode($updateData)
        );
        $this->assertResponseIsSuccessful();
        $updatedTask = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Tâche modifiée', $updatedTask['name']);

        // Suppression de la tâche
        $this->client->request(
            'DELETE',
            "/user-task/delete/$taskId",
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );
        $this->assertResponseStatusCodeSame(200);

        // Vérifie que la tâche n'est plus présente
        $this->client->request(
            'GET',
            '/user-task/get-by-user',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );
        $this->assertResponseIsSuccessful();
        $tasksAfterDelete = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(
            0,
            array_filter($tasksAfterDelete, fn($task) => $task['id'] == $taskId),
            "La tâche supprimée ne doit plus être présente"
        );

    }

}
