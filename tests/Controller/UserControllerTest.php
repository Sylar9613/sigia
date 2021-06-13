<?php
/**
 * Created by PhpStorm.
 * User: Arian-PC
 * Date: 03/03/2020
 * Time: 21:15 PM
 */
namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAccessDeniedForAnonymousUsers(string $httpMethod, string $url)
    {
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $response = $client->getResponse();

        $this->assertResponseRedirects(
            'http://localhost:8000/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public function getUrlsForAnonymousUsers()
    {
        yield ['GET', '/municipio/edit'];
        yield ['GET', '/municipio/show'];
    }

    public function testEditUser()
    {
        $newUserEmail = 'admin_jane@symfony.com';

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jane_admin',
            'PHP_AUTH_PW' => 'kitten',
        ]);
        $crawler = $client->request('GET', '/user/edit');
        $form = $crawler->selectButton('Save changes')->form([
            'user[email]' => $newUserEmail,
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/en/profile/edit', Response::HTTP_FOUND);

        /** @var User $user */
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy([
            'email' => $newUserEmail,
        ]);
        $this->assertNotNull($user);
        $this->assertSame($newUserEmail, $user->getEmail());
    }

    public function testChangePassword()
    {
        $newUserPassword = 'new-password';

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jane_admin',
            'PHP_AUTH_PW' => 'kitten',
        ]);
        $crawler = $client->request('GET', '/en/profile/change-password');
        $form = $crawler->selectButton('Save changes')->form([
            'change_password[currentPassword]' => 'kitten',
            'change_password[newPassword][first]' => $newUserPassword,
            'change_password[newPassword][second]' => $newUserPassword,
        ]);
        $client->submit($form);

        $response = $client->getResponse();

        $this->assertResponseRedirects(
            '/en/logout',
            Response::HTTP_FOUND,
            'Changing password logout the user.'
        );
    }
}