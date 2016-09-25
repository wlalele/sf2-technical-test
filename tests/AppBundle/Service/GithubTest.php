<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\Github;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class GithubTest extends TestCase
{
    const USERNAME_VALID = 'wlalele';
    const USERNAME_INVALID = 'azerjazeoijfqslidfj';

    /** @var Github service */
    private $service = null;

    public function setUp()
    {
        $this->service = new Github();
    }

    public function testIfUserExistOnGithubWithWrongUsername()
    {
        $result = $this->service->isOnGithub(self::USERNAME_INVALID);
        $this->assertEquals(false, $result);
    }

    public function testIfUserExistOnGithubWithGoodUsername()
    {
        $result = $this->service->isOnGithub(self::USERNAME_VALID);
        $this->assertEquals(true, $result);
    }

    public function testGetReposWithWrongUsername()
    {
        $result = $this->service->getUserRepositories(self::USERNAME_INVALID);

        if (property_exists($result, 'message')) {
            $this->assertEquals('Not Found', $result->message);
        } else {
            // Username must be right
        }
    }

    public function testGetReposWithGoodUsername()
    {
        $result = $this->service->getUserRepositories(self::USERNAME_VALID);

        if (is_array($result)) {
            $this->assertEquals(true, count($result) >= 0);
        } else {
            // Username must be wrong
        }
    }
}