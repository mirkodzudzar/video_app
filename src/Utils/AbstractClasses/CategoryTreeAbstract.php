<?php

namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract {

  public $categoriesArrayFromDb;
  protected static $dbConnection;

  public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator) {
    $this->entityManager = $entityManager;
    $this->urlGenerator = $urlGenerator;
    $this->categoriesArrayFromDb = $this->getCategories();
  }

  abstract public function getCategoryList(array $categories_array);

  private function getCategories(): array {

    if (self::$dbConnection) {
      return self::$dbConnection;
    } else {
      $connection = $this->entityManager->getConnection();
      $sql = "SELECT * FROM categories";
      $stmt = $connection->prepare($sql);
      $stmt->execute();

      return self::$dbConnection = $stmt->fetchAll();
    }

  }

}