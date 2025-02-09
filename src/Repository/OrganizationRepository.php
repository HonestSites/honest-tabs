<?php

  namespace App\Repository;

  use App\Entity\Organization;
  use App\Service\AuthenticationManager;
  use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
  use Doctrine\Persistence\ManagerRegistry;

  /**
   * @extends ServiceEntityRepository<Organization>
   */
  class OrganizationRepository extends ServiceEntityRepository
  {

    public function __construct(
      ManagerRegistry               $registry,
      private AuthenticationManager $authManager
    )
    {
      parent::__construct($registry, Organization::class);
    }

    public function getMyOrganizations($active = true): array
    {
      $data = $this->findBy(['active' => $active, 'owner' => $this->authManager->getUser()]);
      dd($data);
      return $data;
    }
  }
