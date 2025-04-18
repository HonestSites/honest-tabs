<?php

namespace App\Repository;

use App\Entity\LinkCollection;
use App\Service\AuthenticationManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\MakerBundle\Security\Model\Authenticator;

/**
 * @extends ServiceEntityRepository<LinkCollection>
 */
class LinkCollectionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry, private readonly AuthenticationManager $authManager)
  {
    parent::__construct($registry, LinkCollection::class);
  }

  public function getOwnedByCategory($categoryId)
  {
    $em = $this->getEntityManager();
    $results = [];
    try {
      $results = $em->createQueryBuilder()
        ->select('lc')
        ->from('App\Entity\LinkCollection', 'lc')
        ->leftJoin('App\Entity\Category', 'c', \Doctrine\ORM\Query\Expr\Join::WITH, 'lc.category = c.id')
        ->leftJoin('App\Entity\Organization', 'o', \Doctrine\ORM\Query\Expr\Join::WITH, 'c.organization = o.id')
        ->where('o.owner = :owner')
        ->andWhere('lc.category = :category')
        ->orderBy('lc.collectionName', 'ASC')
        ->setParameter('owner', $this->authManager->getUser())
        ->setParameter('category', $categoryId)
        ->getQuery()
        ->getResult();
    } catch (\Exception $e) {
    }

    return $results;
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
