<?php
/**
 * SeciurityTest
 */
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 *
 */
class SecurityControllerTest extends WebTestCase
{
    /**
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
