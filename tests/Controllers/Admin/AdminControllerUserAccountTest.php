<?php

namespace App\Tests\Controllers\Admin;

use App\Entity\User;
use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerUserAccountTest extends WebTestCase
{
    use RoleUser;

    public function testUserDeletedAccount() {

        $crawler = $this->client->request('GET', '/admin/delete-account');

        $user = $this->entityManager->getRepository(User::class)->find(2);
        $this->assertNull($user);
    }

    public function testUserEditProfile() {

        $crawler = $this->client->request('GET', '/admin/');

        $form = $crawler->selectButton('Save')->form([
            'user[name]' => 'name',
            'user[last_name]' => 'last name',
            'user[email]' => 'email@gmail.com',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);

        $this->client->submit($form);

        $user = $this->entityManager->getRepository(User::class)->find(2);

        $this->assertSame('name', $user->getName());
        $this->assertSame('last name', $user->getLastName());
        $this->assertSame('email@gmail.com', $user->getEmail());
    }
}
