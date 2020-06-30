<?php

namespace App\Tests\Controllers\Front;

use App\Tests\RoleUser;
use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerSubscriptionTest extends WebTestCase
{
    use RoleUser;

    /**
     * @dataProvider urlsWithVideo
     */
    // Test that logged in user does not sees text for no members
    public function testLoggedInUserDoesNotSeesTextForNoMembers($url) {

        $this->client->request('GET', $url);
        $this->assertNotContains('Video for <b>MEMBERS</b> only.', $this->client->getResponse()->getContent());
    }

    /**
     * @dataProvider urlsWithVideo
     */
    // Test that not logged in user sees text for no members
    public function testNotLoggedInUserSeesTestForNoMembers($url) {

        // Not logged in user
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertContains('Video for <b>MEMBERS</b> only.', $client->getResponse()->getContent());
    }

    // Test that user with expired subscription sees text for no members
    public function testExpiredSubscription() {

        $subscription = $this->entityManager->getRepository(Subscription::class)->find(2);
        $invalid_date = new \Datetime();
        $invalid_date->modify('-1 day');
        $subscription->setValidTo($invalid_date);

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        $this->client->request('GET', '/video-list/category/movies,4');

        $this->assertContains('Video for <b>MEMBERS</b> only.', $this->client->getResponse()->getContent());
    }

    /**
     * @dataProvider urlsWithVideo2
     */
    // Test that not logged in user sees video for no members
    public function testNotLoggedInUserSeesVideosForNoMembers($url) {

        // Not logged in user
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertContains('https://player.vimeo.com/video/113716040', $client->getResponse()->getContent());
    }

    public function urlsWithVideo() {

        yield ['/video-list/category/movies,4'];
        yield ['/search-results?query=movies'];
    }

    public function urlsWithVideo2() {

        yield ['/video-list/category/toys,2/2'];
        yield ['/search-results?query=Movies+3'];
        yield ['/video-details/2#video_comments'];
    }

}
