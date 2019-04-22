<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testProfilePageAccess()
    {
        $this->client->request('GET', '/profile');

        static::assertSame(
            302,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testResetPasswordPageAccess()
    {
        $this->client->request('GET', '/reset-password');

        static::assertSame(
            500,
            $this->client->getResponse()->getStatusCode()
        );
    }
}
