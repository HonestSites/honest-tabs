<?php

  namespace App\Controller;

  use App\Form\OrganizationSelectType;
  use App\Repository\CategoryRepository;
  use App\Repository\LinkCollectionRepository;
  use App\Repository\LinkRepository;
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
      private readonly LinkRepository $linkRepository,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
      $activeOrgId = $request->query->get('orgId');
      $activeCatId = $request->query->get('catId');
      $activeCollectionId = $request->query->get('collectionId');
      $activeLinkId = $request->query->get('linkId');

      $orgs = $this->organizationRepository->getMyOrganizations();
      $activeOrg = null;
      $activeCategory = null;
      $activeCollection = null;
      $activeLink = null;

      // get Active Organization data
      if(! $activeOrgId && isset($orgs[0])) {
        $activeOrgId = $orgs[0]->getId();
      }

      if($activeOrgId) {
        $activeOrg = $this->organizationRepository->findOneById($activeOrgId);
      }

      // get Active Category data
      if(! $activeCatId && $activeOrg) {
        foreach ($activeOrg->getCategories() as $category) {
          if(! $activeCatId) {
            $activeCatId = $category->getId();
          }
        }
      }

      if($activeCatId) {
        $activeCategory = $this->categoryRepository->findOneById($activeCatId);
      }

      // get Active Link Collection data
      if(! $activeCollectionId && $activeCategory) {
        foreach ($activeCategory->getLinkCollections() as $collectionLink) {
          if(! $activeCollectionId) {
            $activeCollectionId = $collectionLink->getId();
          }
        }
      }

      if($activeCollectionId) {
        $activeCollection = $this->linkCollectionRepository->findOneById($activeCollectionId);
      }

      // get Active Link data
      if(! $activeLinkId && $activeCollection) {
        foreach ($activeCollection->getLink() as $link) {
          if(! $activeLinkId) {
            $activeLinkId = $link->getId();
          }
        }
      }

      if($activeLinkId) {
        $activeLink = $this->linkRepository->findOneById($activeLinkId);
      }

      return $this->render('home/index2.html.twig', [
        'organizations' => $orgs,
        'activeOrg' => $activeOrg,
        'activeCategory' => $activeCategory,
        'activeCollection' => $activeCollection,
        'activeLink' => $activeLink,
        'activeOrgId' => $activeOrgId,
        'activeCatId' => $activeCatId,
        'activeLinkId' => $activeLinkId,
        'activeCollectionId' => $activeCollectionId,
      ]);    }



    #[Route('/original', name: 'app_original')]
    public function original(Request $request): Response
    {
      $activeOrgId = $request->query->get('orgId');
      $activeCatId = $request->query->get('catId');
      $activeCollectionId = $request->query->get('collectionId');
      $activeLinkId = $request->query->get('linkId');

      $orgs = $this->organizationRepository->getMyOrganizations();
      $activeOrg = null;
      $activeCategory = null;
      $activeCollection = null;
      $activeLink = null;

      // get Active Organization data
      if(! $activeOrgId && isset($orgs[0])) {
        $activeOrgId = $orgs[0]->getId();
      }

      if($activeOrgId) {
        $activeOrg = $this->organizationRepository->findOneById($activeOrgId);
      }

      // get Active Category data
      if(! $activeCatId && $activeOrg) {
        foreach ($activeOrg->getCategories() as $category) {
          if(! $activeCatId) {
            $activeCatId = $category->getId();
          }
        }
      }

      if($activeCatId) {
        $activeCategory = $this->categoryRepository->findOneById($activeCatId);
      }

      // get Active Link Collection data
      if(! $activeCollectionId && $activeCategory) {
        foreach ($activeCategory->getLinkCollections() as $collectionLink) {
          if(! $activeCollectionId) {
            $activeCollectionId = $collectionLink->getId();
          }
        }
      }

      if($activeCollectionId) {
        $activeCollection = $this->linkCollectionRepository->findOneById($activeCollectionId);
      }

      // get Active Link data
      if(! $activeLinkId && $activeCollection) {
        foreach ($activeCollection->getLink() as $link) {
          if(! $activeLinkId) {
            $activeLinkId = $link->getId();
          }
        }
      }

      if($activeLinkId) {
        $activeLink = $this->linkRepository->findOneById($activeLinkId);
      }

      return $this->render('home/index.html.twig', [
        'organizations' => $orgs,
        'activeOrg' => $activeOrg,
        'activeCategory' => $activeCategory,
        'activeCollection' => $activeCollection,
        'activeLink' => $activeLink,
        'activeOrgId' => $activeOrgId,
        'activeCatId' => $activeCatId,
        'activeLinkId' => $activeLinkId,
        'activeCollectionId' => $activeCollectionId,
      ]);
    }

  }
