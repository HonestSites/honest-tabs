<?php

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Link>
 */
class LinkRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Link::class);
  }

  public function save($link)
  {
    try {
      $this->getEntityManager()->persist($link);
      $this->getEntityManager()->flush();
    } catch (\Exception $e) {
      $link = null;
    }
    return $link;
  }

}
