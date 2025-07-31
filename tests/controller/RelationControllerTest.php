<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RelationControllerTest extends WebTestCase
{
    public function testJoinRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('POST', '/relation/join', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testSourceAndTargetRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/relation/source-target/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testAnswerRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/relation/answers/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }
}
