<?php

  namespace App\Controller;

  use App\Repository\CategoryRepository;
  use App\Repository\LinkCollectionRepository;
  use App\Repository\OrganizationRepository;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Attribute\Route;

  final class HomeController extends AbstractController
  {
    public function __construct(
      private readonly OrganizationRepository $organizationRepository,
      private readonly CategoryRepository $categoryRepository,
      private readonly LinkCollectionRepository $linkCollectionRepository,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
      $activeOrgId = $request->query->get('orgId');
      $activeCatId = $request->query->get('catId');
      $activeCollectionId = $request->query->get('collectionId');
      $orgs = $this->organizationRepository->getMyOrganizations();
      $activeOrg = null;
      $activeCategory = null;
      $activeCollection = null;

      if(! $activeOrgId && isset($orgs[0])) {
        $activeOrgId = $orgs[0]->getId();
      }

      if($activeOrgId) {
        $activeOrg = $this->organizationRepository->findOneById($activeOrgId);
      }

      if(! $activeCatId && $activeOrg) {
        foreach ($activeOrg->getCategories() as $category) {
          if(! $activeCatId) {
            $activeCatId = $category->getId();
          }
        }
      }
      // get Active Category data
      if($activeCatId) {
        $activeCategory = $this->categoryRepository->findOneById($activeCatId);
      }

      if(! $activeCollectionId && $activeCategory) {
        foreach ($activeCategory->getLinkCollections() as $collectionLink) {
          if(! $activeCollectionId) {
            $activeCollectionId = $collectionLink->getId();
          }
        }
      }

      // get Active Collection data
      if($activeCollectionId) {
        $activeCollection = $this->linkCollectionRepository->findOneById($activeCollectionId);
      }

      return $this->render('home/index.html.twig', [
        'organizations' => $orgs,
        'activeOrg' => $activeOrg,
        'activeCategory' => $activeCategory,
        'activeCollection' => $activeCollection,
        'activeOrgId' => $activeOrgId,
        'activeCatId' => $activeCatId,
        'activeCollectionId' => $activeCollectionId,
      ]);
    }

  }
