<?php

namespace App\Tests\Controllers\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerSecurityTest extends WebTestCase
{
    /**
     * @dataProvider  getUrlsForRegularUsers
     *
     * @param string $httpMethod
     * @param string $url
     * @return void
     */
    public function testAccessDeniedForRegularUsers(string $httpMethod, string $url) {

        // Regular user
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'marko@gmail.com',
            'PHP_AUTH_PW' => 'marko',
        ]);

        $client->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function getUrlsForRegularUsers() {

        yield ['GET', '/admin/su/categories'];
        yield ['GET', '/admin/su/edit-category/1'];
        yield ['GET', '/admin/su/delete-category/1'];
        yield ['GET', '/admin/su/users'];
        yield ['GET', '/admin/su/upload-video'];
    }

    // Super admin user
    public function testAdminSu() {

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'mirko@gmail.com',
            'PHP_AUTH_PW' => 'mirko',

        ]);

        $crawler = $client->request('GET', '/admin/su/categories');
        $this->assertSame('Categories list', $crawler->filter('h2')->text());
    }
}
