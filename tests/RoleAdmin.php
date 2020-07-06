<?php

namespace App\Tests;

trait RoleAdmin {

  public function setUp() {

    // Super admin user
    parent::setUp();
    // Clearing the cache
    self::bootKernel();
    // returns the real and unchanged service container
    $container = self::$kernel->getContainer();
    // gets the special container that allows fetching private services
    $container = self::$container;
    $cache = self::$container->get('App\Utils\Interfaces\CacheInterface');
    $this->cache = $cache->cache;
    $this->cache->clear();

    $this->client = static::createClient([], [
        'PHP_AUTH_USER' => 'mirko@gmail.com',
        'PHP_AUTH_PW' => 'mirko'
    ]);
    // Prevents from shutting down the kernel between test request and thus losing transactions.
    // $this->client->disableReboot();

    $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    // $this->entityManager->beginTransaction();
    // $this->entityManager->getConnection()->setAutoCommit(false);
  }

  public function tearDown() {

      parent::tearDown();
      $this->cache->clear();
      // This is used for rolling back DB data for not making any changes.
      // $this->entityManager->rollback();
      $this->entityManager->close();
      $this->entityManager = null; //prevent memory leaks
  }
}