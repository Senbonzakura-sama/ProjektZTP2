<?php
/**
 * TagControllerTest.
 */
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TagControllerTest
 */
class TagControllerTest extends WebTestCase
{
    /**
     * testIndex.
     * @return void
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/tag');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * testShow.
     * @return void
     */
    public function testShow()
    {
        $client = static::createClient();
        $client->request('GET', '/tag/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
