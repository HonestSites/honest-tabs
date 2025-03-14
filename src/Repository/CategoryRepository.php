<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Category::class);
  }

  public function save($category)
  {
    try {
      $this->getEntityManager()->persist($category);
      $this->getEntityManager()->flush();
    } catch (\Exception $e) {
      $category = null;
    }
    return $category;
  }

}
