<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    public function testRegister()
    {

        $client = static::createClient();

        // Données pour la création de l'utilisateur
        $data = [
            'username' => 'UserTest',
            'firstname' => 'User',
            'lastname' => 'Test',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ];

        // Effectuer une requête POST à l'API
        $client->request('POST', '/user/register', $data);

        // dd($client);

        // Vérifier que la réponse est réussie (code 201 Created)
        // $this->assertResponseStatusCodeSame(201);

        // Vérifier le contenu de la réponse
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        // dd($client);

        // $this->assertArrayHasKey('id', $content); // Vérifie que l'ID de l'utilisateur a été retourné
        // $this->assertEquals('testuser', $content['username']); // Vérifie le nom d'utilisateur
        // $this->assertEquals('testuser@example.com', $content['email']); // Vérifie l'email
    }
}
