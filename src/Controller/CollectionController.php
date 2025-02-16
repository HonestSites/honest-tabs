<?php

namespace App\Controller;

use App\Entity\LinkCollection;
use App\Form\LinkCollectionType;
use App\Repository\CategoryRepository;
use App\Repository\LinkCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/collection')]
final class CollectionController extends AbstractController
{
  #[Route(name: 'app_collection_index', methods: ['GET'])]
  public function index(LinkCollectionRepository $linkCollectionRepository): Response
  {
    return $this->render('collection/index.html.twig', [
      'linkCollections' => $linkCollectionRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'app_collection_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
  {
    $linkCollection = new LinkCollection();

    $categoryId = $request->query->get('catId');
    $category = $categoryId ? $categoryRepository->findOneById($categoryId) : null;
    $linkCollection->setCategory($category);


    $form = $this->createForm(LinkCollectionType::class, $linkCollection);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($linkCollection);
      $entityManager->flush();

      if ($request->isXmlHttpRequest()) {
        return new Response(null, 204);
      }

      return $this->redirectToRoute('app_collection_index', [], Response::HTTP_SEE_OTHER);
    }


    $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

    return $this->render("collection/{$template}", [
      'linkCollections' => $linkCollection,
      'isHttpRequest' => (bool)$request->isXmlHttpRequest(),
      'form' => $form->createView(),
    ], new Response(
      null,
      $form->isSubmitted() ? 422 : 200
    ));

  }

  #[Route('/{id}', name: 'app_collection_show', methods: ['GET'])]
  public function show(LinkCollection $linkCollection): Response
  {
    return $this->render('collection/show.html.twig', [
      'linkCollections' => $linkCollection,
    ]);
  }

  #[Route('/{id}/edit', name: 'app_collection_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, LinkCollection $linkCollection, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(LinkCollectionType::class, $linkCollection);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_collection_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('collection/edit.html.twig', [
      'linkCollections' => $linkCollection,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_collection_delete', methods: ['POST'])]
  public function delete(Request $request, LinkCollection $linkCollection, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $linkCollection->getId(), $request->getPayload()->getString('_token'))) {
      $entityManager->remove($linkCollection);
      $entityManager->flush();
    }

    return $this->redirectToRoute('app_collection_index', [], Response::HTTP_SEE_OTHER);
  }
}
