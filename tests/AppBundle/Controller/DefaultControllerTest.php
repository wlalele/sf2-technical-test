<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AppBundle\Service\GithubTest;

class DefaultControllerTest extends WebTestCase
{
    const COMMENT_ROUTE = '/%s/comment';

    private $client = null;
    private $auth_client = null;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->auth_client = $this->createAuthorizedClient();
    }

    /**
     * @return Client
     */
    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => 'wlalele'));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_' . $firewallName,
            serialize($container->get('security.token_storage')->getToken()));
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    public function testRedirectionToLoginWhenAccessingHomepage()
    {
        $this->client->request('GET', '/');
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
    }

    public function testRedirectionToLoginWhenAccessingCommentPage()
    {
        $this->client->request('GET', sprintf(self::COMMENT_ROUTE, GithubTest::USERNAME_VALID));
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));
    }

    public function testRedirectionWhenAccessingCommentPageWithGoodUsername()
    {
        $this->auth_client->followRedirects();
        $crawler = $this->auth_client->request('GET', sprintf(self::COMMENT_ROUTE, GithubTest::USERNAME_VALID));

        $this->assertEquals(
            1,
            $crawler->filter('h3.panel-title:contains("comment")')->count()
        );
    }

    public function testRedirectionWhenAccessingCommentPageWithWrongUsername()
    {
        $this->auth_client->followRedirects();
        $crawler = $this->auth_client->request('GET', sprintf(self::COMMENT_ROUTE, GithubTest::USERNAME_INVALID));

        $this->assertEquals(
            0,
            $crawler->filter('h3.panel-title:contains("comment")')->count()
        );
    }
}
