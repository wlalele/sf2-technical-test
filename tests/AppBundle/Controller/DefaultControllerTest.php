<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testRedirectionToLoginWhenAccessingHomepage()
    {
        $this->client->request('GET', '/');
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
    }

    public function testRedirectionToLoginWhenAccessingCommentPage()
    {
        $this->client->request('GET', '/wlalele/comment');
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
    }
}
