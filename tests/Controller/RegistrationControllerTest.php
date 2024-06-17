<?php
/**
 * RegistrationControllerTest.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RegistrationControllerTest.
 */
class RegistrationControllerTest extends WebTestCase
{
    /**
     * testRegister.
     *
     * @return void
     */
    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name=user]')->form();
        $form['user[email]'] = 'test@example.com';
        $form['user[password][first]'] = 'test_password';
        $form['user[password][second]'] = 'test_password';
        $form['user[nickname]'] = 'test_user';

        $client->submit($form);

        $this->assertResponseRedirects('/question');

        $client->followRedirect();
        $this->assertSelectorExists('.alert-success');
        // $this->assertSelectorTextContains('.alert-success', 'Rejestracja zakończona pomyślnie');
    }
}
