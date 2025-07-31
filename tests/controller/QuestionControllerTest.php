<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuestionControllerTest extends WebTestCase
{
    public function testShowQuestionRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/question/show/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testListQuestionRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/question/list');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testListRootQuestionRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/question/list-root');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testCreateQuestionRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('POST', '/question/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testEditQuestionRequiresAuthentication()
    {
        $client = static::createClient();
        $client->request('PATCH', '/question/edit/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([]));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }
}
