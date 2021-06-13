<?php

/**
 * Created by PhpStorm.
 * User: Arian-PC
 * Date: 03/03/2020
 * Time: 19:44 PM
 */

namespace App\Tests;

use App\Entity\Auditor;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPublicUrls($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
    }

    public function testLocalidad()
    {
        $client = static::createClient();
        // the service container is always available via the test client
        $auditor = $client->getContainer()->get('doctrine')->getRepository(Auditor::class)->find(1);
        $client->request('GET', sprintf('/localidad/%s', $auditor->getLocalidad()));

        $this->assertResponseIsSuccessful();
    }

    /**
     * The application contains a lot of secure URLs which shouldn't be
     * publicly accessible. This tests ensures that whenever a user tries to
     * access one of those pages, a redirection to the login form is performed.
     *
     * @dataProvider getSecureUrls
     */
    public function testSecureUrls(string $url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();

        $this->assertResponseRedirects(
            'http://localhost:8000/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/login'];
        yield ['/municipio/'];
        yield ['/localidad/'];
        yield ['/organismo/'];
        yield ['/osde/'];
        yield ['/entidad/'];
        yield ['/nivel/'];
        yield ['/cargo/'];
        yield ['/plazas/'];
        yield ['/auditor/'];
    }

    public function getSecureUrls()
    {
        yield ['/user/'];
        yield ['/user/new'];
        yield ['/user/1'];
        yield ['/user/1/edit'];
    }
}