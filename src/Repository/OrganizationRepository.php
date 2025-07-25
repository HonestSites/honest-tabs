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
      return $this->findBy(['active' => $active, 'owner' => $this->authManager->getUser()], ['organizationName' => 'ASC']);
    }

    public function getMyOrganizationById($orgId)
    {
      return $this->findOneBy(['id' => $orgId, 'owner' => $this->authManager->getUser()]);
    }

    public function save($organization)
    {
      try {
        $this->getEntityManager()->persist($organization);
        $this->getEntityManager()->flush();
      } catch (\Exception $e) {
        $organization = null;
      }
      return $organization;
    }
  }
