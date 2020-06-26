<?php

namespace App\Tests\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerSecurityTest extends WebTestCase
{
    /**
     * @dataProvider getSecureUrls
     *
     * @param string $url
     * @return void
     */
    public function testSecureUrls(string $url)
    {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertContains('/login', $client->getResponse()->getTargetUrl());
    }

    public function getSecureUrls() {

        yield ['/admin'];
        yield ['/admin/videos'];
        yield ['/admin/su/categories'];
        yield ['/admin/su/edit-category/1'];
        yield ['/admin/su/delete-category/1'];
        yield ['/admin/su/upload-video'];
        yield ['/admin/su/users'];
    }

    public function testVideoForMembersOnly() {

        $client = static::createClient();
        $client->request('GET', '/video-list/category/movies,4');

        $this->assertContains('Video for <b>MEMBERS</b> only.', $client->getResponse()->getContent());
    }
}
