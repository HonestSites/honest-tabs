<?php

namespace App\Repository;

use App\Entity\LinkCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkCollection>
 */
class LinkCollectionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, LinkCollection::class);
  }

  public function save($collection)
  {
    try {
      $this->getEntityManager()->persist($collection);
      $this->getEntityManager()->flush();
    } catch (\Exception $e) {
      $collection = null;
    }
    return $collection;
  }

}
