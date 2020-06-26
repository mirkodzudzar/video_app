<?php

namespace App\Tests\Controllers\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;

class AdminControllerCategoriesTest extends WebTestCase
{
    public function setUp() {

        parent::setUp();
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'mirko@gmail.com',
            'PHP_AUTH_PW' => 'mirko'
        ]);
        // Prevents from shutting down the kernel between test request and thus losing transactions.
        $this->client->disableReboot();

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager->beginTransaction();
        $this->entityManager->getConnection()->setAutoCommit(false);
    }

    public function tearDown() {

        parent::tearDown();
        // This is used for rolling back DB data for not making any changes.
        $this->entityManager->rollback();
        $this->entityManager->close();
        $this->entityManager = null; //prevent memory leaks
    }

    public function testTextOnPage() {

        $crawler = $this->client->request('GET', '/admin/su/categories');
        $this->assertSame('Categories list', $crawler->filter('h2')->text());
        $this->assertContains('Electronics', $this->client->getResponse()->getContent());
    }

    public function testNumberOfItems() {

        $crawler = $this->client->request('GET', '/admin/su/categories');
        $this->assertCount(21, $crawler->filter('option'));
    }

    public function testNewCategory() {

        $crawler = $this->client->request('GET', '/admin/su/categories');

        $form = $crawler->selectButton('Add')->form([
            'category[name]' => 'Other electronics',
            'category[parent]' => 1,
        ]);
        $this->client->submit($form);

        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => 'Other electronics']);

        $this->assertNotNull($category);
        $this->assertSame('Other electronics', $category->getName());
    }

    public function testEditCategory() {

        $crawler = $this->client->request('GET', '/admin/su/edit-category/1');

        $form = $crawler->selectButton('Save')->form([
            'category[name]' => 'Electronics 2',
            'category[parent]' => 1,
        ]);
        $this->client->submit($form);

        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => 'Electronics 2']);

        $this->assertNotNull($category);
        $this->assertSame('Electronics 2', $category->getName());

    }

    public function testDeleteCategory() {

        $crawler = $this->client->request('GET', '/admin/su/delete-category/1');

        $category = $this->entityManager->getRepository(Category::class)->find(1);
        $this->assertNull($category);
    }
}
