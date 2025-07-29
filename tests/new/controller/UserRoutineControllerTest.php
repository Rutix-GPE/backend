<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Routine;
use App\Entity\UserRoutine;
use App\Repository\UserRepository;
use App\Repository\RoutineRepository;
use App\Repository\UserRoutineRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRoutineControllerTest extends WebTestCase
{
    private $client;
    private $userRepository;
    private $routineRepository;
    private $userRoutineRepository;
    private $em;

    // public static function setUpBeforeClass(): void
    // {
    //     exec('php bin/console app:db:rebuild --env=test');
    // }

    // protected function setUp(): void
    // {
    //     $this->client = static::createClient();
    //     $container = $this->client->getContainer();
    //     $this->userRepository = $container->get(UserRepository::class);
    //     $this->routineRepository = $container->get(RoutineRepository::class);
    //     $this->userRoutineRepository = $container->get(UserRoutineRepository::class);
    //     $this->em = $container->get('doctrine')->getManager();
    // }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        $this->routineRepository = $container->get(RoutineRepository::class);
        $this->userRoutineRepository = $container->get(UserRoutineRepository::class);
        $this->em = $container->get('doctrine')->getManager();

        $connection = $this->em->getConnection();

        // 1. Supprime tous les ENFANTS d'abord !
        $connection->executeStatement('DELETE FROM relation');
        // Ajoute ici d'autres tables dépendantes de routine si besoin
        $connection->executeStatement('DELETE FROM user_task');
        $connection->executeStatement('DELETE FROM user_routine');

        // 2. Puis les tables PARENTS
        $connection->executeStatement('DELETE FROM routine');
        $connection->executeStatement('DELETE FROM user');

        // Reset auto-increment (optionnel)
        $connection->executeStatement('ALTER TABLE relation AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE user_task AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE user_routine AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE routine AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE user AUTO_INCREMENT = 1');
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

    private function createRoutineForUser($user, $name, $desc, $days, $taskTime, $isAllTaskGenerated = true)
    {
        // Création de Routine
        $routine = new Routine();
        $routine->setName($name);
        $routine->setDescription($desc);
        $routine->setDays($days);
        $routine->setTaskTime(new \DateTime($taskTime));
        $routine->setCreationDate(new \DateTime());
        $routine->setUpdatedDate(new \DateTime());
        $this->em->persist($routine);

        // Création de UserRoutine
        $userRoutine = new UserRoutine();
        $userRoutine->setUser($user);
        $userRoutine->setName($name);
        $userRoutine->setDescription($desc);
        $userRoutine->setDays($days);
        $userRoutine->setTaskTime(new \DateTime($taskTime));
        $userRoutine->setIsAllTaskGenerated($isAllTaskGenerated);
        $userRoutine->setCreationDate(new \DateTime());
        $userRoutine->setUpdatedDate(new \DateTime());
        $this->em->persist($userRoutine);

        $this->em->flush();

        return $userRoutine;
    }

    public function testGetRoutinesByUser(): void
    {
        $userInfo = $this->createAndAuthenticateUser('routineuser', 'routineuser@test.com');
        $user = $userInfo['user'];
        $token = $userInfo['token'];

        $this->createRoutineForUser(
            $user,
            "Horaire étudiant",
            "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
            [1,2,3,4,5],
            '1970-01-01T08:30:00+00:00'
        );
        $this->createRoutineForUser(
            $user,
            "Médicament",
            "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
            [1,2,3,4,5,6,7],
            '1970-01-01T09:00:00+00:00'
        );

        $this->client->request(
            'GET',
            '/routine/get-by-user',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertGreaterThanOrEqual(2, count($data));

        // Vérifie qu'on retrouve bien les routines créées
        $names = array_column($data, 'name');
        $this->assertContains('Horaire étudiant', $names);
        $this->assertContains('Médicament', $names);

        // Vérifie la structure d'une routine
        $routine = $data[0];
        $this->assertArrayHasKey('id', $routine);
        $this->assertArrayHasKey('name', $routine);
        $this->assertArrayHasKey('description', $routine);
        $this->assertArrayHasKey('days', $routine);
        $this->assertArrayHasKey('taskTime', $routine);
        $this->assertArrayHasKey('isAllTaskGenerated', $routine);
        $this->assertArrayHasKey('creationDate', $routine);
        $this->assertArrayHasKey('updatedDate', $routine);
    }
}
