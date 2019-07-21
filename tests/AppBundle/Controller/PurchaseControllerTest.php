<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadPurchaseData;
use AppBundle\DataFixtures\ORM\LoadProductData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testGeneratePurchase()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new LoadProductData();
        $productFaker = $fixture->load($entityManager);

        $idProduct = $productFaker->getId();
        $name = 'luis';
        $email = 'algo@dominio.com';
        $address = 'calle con carrera';
        $mobile = random_int(312000, 318000);


        $data = [
            'idProduct' => $idProduct,
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'address' => $address,
        ];
        $crawler = $client->request('POST', '/Purchase/generatePurchase', $data);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue(is_array($content));
        $this->assertArrayHasKey('success', $content);
        $this->assertTrue($content['success']);
        $this->assertArrayHasKey('message', $content);
        $this->assertTrue(is_string($content['message']));
        $this->assertArrayHasKey('data', $content);
        $this->assertTrue(is_array($content['data']));

        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT COUNT(o.id) from AppBundle:Purchase o WHERE o.customerName = :name AND o.customerEmail = :email AND o.customerMobile = :mobile AND o.customerAddress = :address AND o.product = :product');
        $query->setParameter('name', $name);
        $query->setParameter('email', $email);
        $query->setParameter('mobile', $mobile);
        $query->setParameter('address', $address);
        $query->setParameter('product', $idProduct);
        $this->assertTrue('1' === $query->getSingleScalarResult());

    }

    public function testListPurchase()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new LoadPurchaseData();
        $fixture->load($entityManager);

        $crawler = $client->request('GET', '/Purchase/listPurchase');

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
        $this->assertArrayHasKey('product', $content['data'][0]);
        $this->assertTrue(is_array($content['data'][0]['product']));
        $this->assertArrayHasKey('customerName', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['customerName']));
        $this->assertArrayHasKey('customerEmail', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['customerEmail']));
        $this->assertArrayHasKey('customerMobile', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['customerMobile']));
        $this->assertArrayHasKey('customerAddress', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['customerAddress']));
        $this->assertArrayHasKey('status', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['status']));
        $this->assertArrayHasKey('createdAt', $content['data'][0]);
        $this->assertTrue(is_array($content['data'][0]['createdAt']));
        $this->assertArrayHasKey('updatedAt', $content['data'][0]);
        $this->assertTrue(is_array($content['data'][0]['updatedAt']));
        $this->assertArrayHasKey('reference', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['reference']));
        $this->assertArrayHasKey('description', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['description']));
        $this->assertArrayHasKey('currency', $content['data'][0]);
        $this->assertTrue(is_string($content['data'][0]['currency']));
        $this->assertArrayHasKey('total', $content['data'][0]);
        $this->assertTrue(is_int($content['data'][0]['total'][0]));
    }

}

