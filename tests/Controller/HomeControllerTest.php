<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testHomePageIsUp()
    {
        $this->client->request('GET', '/');

        static::assertSame(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testShowPageIsUp()
    {
        $this->client->request('GET', '/trick/12');

        static::assertSame(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testShowPageNotExist()
    {
        $this->client->request('GET', '/trick/999');

        static::assertSame(
            404,
            $this->client->getResponse()->getStatusCode()
        );
    }

}
