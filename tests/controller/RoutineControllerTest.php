<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RoutineControllerTest extends WebTestCase
{
    public function testShowRoutineRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/routine/show/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testListRoutineRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/routine/list');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testCreateRoutineRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('POST', '/routine/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testEditRoutineRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('PATCH', '/routine/edit/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }
}
