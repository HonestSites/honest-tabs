<?php

  namespace App\Controller;

  use App\Entity\Organization;
  use App\Form\OrganizationType;
  use App\Repository\OrganizationRepository;
  use App\Service\AuthenticationManager;
  use Doctrine\ORM\EntityManagerInterface;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Attribute\Route;
  use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

  #[Route('/organization')]
  final class OrganizationController extends AbstractController
  {
    public function __construct(
      private AuthenticationManager $authenticationManager,
    )
    {
    }

    #[Route(name: 'app_organization_index', methods: ['GET'])]
    public function index(Request $request, OrganizationRepository $organizationRepository): Response
    {

      return $this->render("organization/index.html.twig", [
        'organizations' => $organizationRepository->findAll(),
      ]);
    }

    #[Route('/new', name: 'app_organization_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
      $organization = new Organization();
      $form = $this->createForm(OrganizationType::class, $organization);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $organization->setOwner($this->authenticationManager->getUser());
        $entityManager->persist($organization);
        $entityManager->flush();
        if($request->isXmlHttpRequest()) {
          return new Response(null, 204);
        }
        return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
      }

      $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

      return $this->render("organization/{$template}", [
        'organization' => $organization,
        'form' => $form,
        'isHttpRequest' => (bool) $request->isXmlHttpRequest(),
      ], new Response(
        null,
        $form->isSubmitted() ? 422 : 200
      ));
    }

    #[Route('/{id}', name: 'app_organization_show', methods: ['GET'])]
    public function show(Organization $organization): Response
    {
      return $this->render('organization/show.html.twig', [
        'organization' => $organization,
      ]);
    }

    #[Route('/{id}/edit', name: 'app_organization_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organization $organization, EntityManagerInterface $entityManager): Response
    {
      $form = $this->createForm(OrganizationType::class, $organization);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        if($request->isXmlHttpRequest()) {
          return new Response(null, 204);
        }
        return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
      }

      return $this->render('organization/edit.html.twig', [
        'organization' => $organization,
        'form' => $form,
      ], new Response(
        null,
        $form->isSubmitted() ? 422 : 200
      ));
    }

    #[Route('/{id}', name: 'app_organization_delete', methods: ['POST'])]
    public function delete(Request $request, Organization $organization, EntityManagerInterface $entityManager): Response
    {
      if ($this->isCsrfTokenValid('delete' . $organization->getId(), $request->getPayload()->getString('_token'))) {
        $entityManager->remove($organization);
        $entityManager->flush();
      }

      return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
    }
  }
