<?php

namespace App\Tests;

trait RoleAdmin {

  public function setUp() {

    // Super admin user
    parent::setUp();
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
      // This is used for rolling back DB data for not making any changes.
      // $this->entityManager->rollback();
      $this->entityManager->close();
      $this->entityManager = null; //prevent memory leaks
  }
}