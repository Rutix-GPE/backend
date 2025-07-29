<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserResponseControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;

    public static function setUpBeforeClass(): void
    {
        // Important : recharge les fixtures avant les tests !
        exec('php bin/console app:db:rebuild --env=test');
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get(UserRepository::class);
    }

    private function createAndAuthenticateUser($username, $email, $password = 'password123')
    {
        $user = new User();
        $user->setUsername($username);
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->userRepository->add($user, true);

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

    public function testFirstQuestionAndNextQuestion(): void
    {
        $userInfo = $this->createAndAuthenticateUser('userresp', 'userresp@test.com');
        $token = $userInfo['token'];

        // 1. /user-response/first-question
        $this->client->request(
            'GET',
            '/user-response/first-question',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );

        $this->assertResponseIsSuccessful();
        $firstQuestion = json_decode($this->client->getResponse()->getContent(), true);

        // Vérifie la structure attendue
        $this->assertArrayHasKey('question', $firstQuestion);
        $this->assertArrayHasKey('answer', $firstQuestion);
        $this->assertArrayHasKey('url', $firstQuestion);

        // On s'attend à une question de type "As-tu un emploi ?" (adapter selon tes fixtures !)
        $this->assertEquals('As-tu un emploi ?', $firstQuestion['question']);
        $this->assertContains('Oui', $firstQuestion['answer']);
        $this->assertContains('Non', $firstQuestion['answer']);
        $this->assertEquals('user-response/next-question/1', $firstQuestion['url']);

        // 2. /user-response/next-question/1
        $this->client->request(
            'POST',
            '/user-response/next-question/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode(['answer' => 'Non'])
        );

        $this->assertResponseIsSuccessful();
        $nextQuestion = json_decode($this->client->getResponse()->getContent(), true);

        // Vérifie la structure attendue
        $this->assertArrayHasKey('question', $nextQuestion);
        $this->assertArrayHasKey('answer', $nextQuestion);
        $this->assertArrayHasKey('url', $nextQuestion);

        // S'attend à la 2e question (adapter selon tes fixtures)
        $this->assertEquals('Es-tu étudiant ?', $nextQuestion['question']);
        $this->assertContains('Oui', $nextQuestion['answer']);
        $this->assertContains('Non', $nextQuestion['answer']);
        $this->assertEquals('user-response/next-question/2', $nextQuestion['url']);
    }

    public function testNextQuestionMissingAnswer(): void
    {
        $userInfo = $this->createAndAuthenticateUser('missinganswer', 'missinganswer@test.com');
        $token = $userInfo['token'];

        $this->client->request(
            'POST',
            '/user-response/next-question/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $token
            ],
            json_encode([]) // pas de "answer"
        );

        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Missing informations', $data['error']);
    }
}
