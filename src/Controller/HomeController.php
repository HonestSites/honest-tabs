<?php

  namespace App\Controller;

  use App\Repository\OrganizationRepository;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Attribute\Route;

  final class HomeController extends AbstractController
  {
    public function __construct(
      private readonly OrganizationRepository $organizationRepository,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
      return $this->render('home/index.html.twig', [
        'organizations' => $this->organizationRepository->getMyOrganizations(),
      ]);
    }
  }
