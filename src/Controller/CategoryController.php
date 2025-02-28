<?php

  namespace App\Controller;

  use App\Entity\Category;
  use App\Form\CategoryType;
  use App\Repository\CategoryRepository;
  use App\Repository\OrganizationRepository;
  use Doctrine\ORM\EntityManagerInterface;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Attribute\Route;

  #[Route('/category')]
  final class CategoryController extends AbstractController
  {
    public function __construct(
      private readonly OrganizationRepository $organizationRepository
    )
    {}

    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
      return $this->render('category/index.html.twig', [
        'categories' => $categoryRepository->findBy([], ['categoryName' => 'ASC']),
      ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
      $category = new Category();
      $orgId = $request->query->get('orgId');
      $organization = $orgId ? $this->organizationRepository->findOneById($orgId) : null;
      $category->setOrganization($organization);
      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($category);
        $entityManager->flush();

        if($request->isXmlHttpRequest()) {
          return new Response(null, 204);
        }
        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
      }

      $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

      return $this->render("category/{$template}", [
        'category' => $category,
        'isHttpRequest' => (bool)$request->isXmlHttpRequest(),
        'form' => $form->createView(),
      ], new Response(
        null,
        $form->isSubmitted() ? 422 : 200
      ));
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
      return $this->render('category/show.html.twig', [
        'category' => $category,
      ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
      }

      return $this->render('category/edit.html.twig', [
        'category' => $category,
        'form' => $form,
      ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
      if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->getPayload()->getString('_token'))) {
        $entityManager->remove($category);
        $entityManager->flush();
      }

      return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
  }
