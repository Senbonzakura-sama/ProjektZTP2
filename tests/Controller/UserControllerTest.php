<?php
/**
 * userControllerTest.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class userControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * testIndex,.
     *
     * @return void
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/user');

        $this->assertResponseIsSuccessful();
    }
}
