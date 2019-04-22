<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAdminPageIsNotAccessible()
    {
        $this->client->request('GET', '/admin');

        static::assertSame(
            302,
            $this->client->getResponse()->getStatusCode()
        );
    }
}
