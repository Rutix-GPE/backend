<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvatarControllerTest extends WebTestCase
{
    private $expectedFiles = [
        "049ec39b121d26f40a9bf5620a821857.png",
        "932bf794d8f336aaf1b64286b55c2d13.png",
        "9cb497f79fb15740b389564c5bbf269b.png",
        "Boy_1.png",
        "afd7e2d528ac4fe93beccf6d90babb99.png",
        "avatar_par_defaut.png"
    ];

    public function testListAvatar(): void
    {
        $client = static::createClient();
        $client->request('GET', '/avatar/list');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        foreach ($this->expectedFiles as $filename) {
            $this->assertContains($filename, $data, "Le fichier $filename doit être présent dans le résultat");
        }

        $this->assertCount(count($this->expectedFiles), $data, "La liste doit contenir exactement les fichiers attendus");
    }

    /**
     * @depends testListAvatar
     */
    public function testEachAvatarIsAccessible(): void
    {
        $client = static::createClient();

        foreach ($this->expectedFiles as $filename) {
            $client->request('GET', '/avatar/get/' . $filename);

            $this->assertResponseIsSuccessful("L'avatar $filename doit être accessible en GET");
            $this->assertSame('image/png', $client->getResponse()->headers->get('content-type'), "Le Content-Type doit être image/png pour $filename");
            $this->assertNotEmpty($client->getResponse()->getContent(), "L'image $filename ne doit pas être vide");
        }
    }
}
