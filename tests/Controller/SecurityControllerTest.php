<?php
/**
 * SeciurityTest.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * testLogin.
     * @return void
     */
    public function testLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Proszę, zaloguj się');
    }
}
