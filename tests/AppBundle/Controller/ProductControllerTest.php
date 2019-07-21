<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Product/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue(is_array($content));
        $this->assertArrayHasKey('success', $content);
        $this->assertTrue($content['success']);
        $this->assertArrayHasKey('message', $content);
        $this->assertTrue(is_string($content['message']));
        $this->assertArrayHasKey('data', $content);
        $this->assertTrue(is_array($content['data']));
        $this->assertArrayHasKey('id', $content['data'][0]);
        $this->assertTrue(is_int($content['data'][0]['id']));
        $this->assertArrayHasKey('name', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['name']));
        $this->assertArrayHasKey('price', $content['data'][0]);
        $this->assertTrue(is_int($content['data'][0]['price']));
    }

}
