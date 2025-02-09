<?php

  namespace App\Controller;

  use App\Repository\CategoryRepository;
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
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
      $activeOrgId = $request->query->get('orgId');
      $activeCatId = $request->query->get('catId');
      $orgs = $this->organizationRepository->getMyOrganizations();
      $activeOrg = $this->organizationRepository->findOneById($activeOrgId);
      return $this->render('home/index.html.twig', [
        'organizations' => $orgs,
        'activeOrg' => (! $activeOrg && isset($orgs[0])) ? $orgs[0] : $activeOrg,
        'activeCatId' => $activeCatId,
      ]);
    }

  }
