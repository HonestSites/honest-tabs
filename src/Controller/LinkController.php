<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkCollectionRepository;
use App\Repository\LinkRepository;
use App\Service\PasswordManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/link')]
final class LinkController extends AbstractController
{
  #[Route(name: 'app_link_index', methods: ['GET'])]
  public function index(LinkRepository $linkRepository): Response
  {
    return $this->render('link/index.html.twig', [
      'links' => $linkRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'app_link_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager, LinkCollectionRepository $linkCollectionRepository, PasswordManager $passwordManager, LoggerInterface $logger): Response
  {
    $link = new Link();

    $collectionId = $request->query->get('collectionId');
    $collection = $collectionId ? $linkCollectionRepository->findOneById($collectionId) : null;
    $link->setLinkCollection($collection);


    $form = $this->createForm(LinkType::class, $link);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if($link->getSitePassword()) {
        $encData = $passwordManager->encryptPassword($link->getSitePassword());
        $link->setSitePassword($encData['cipherText']);
        $link->setEncData($encData);
      }
      $entityManager->persist($link);
      $entityManager->flush();

      if ($request->isXmlHttpRequest()) {
        return new Response(null, 204);
      }

      return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
    }

    $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

    return $this->render("link/{$template}", [
      'link' => $link,
      'isHttpRequest' => (bool)$request->isXmlHttpRequest(),
      'form' => $form->createView(),
    ], new Response(
      null,
      $form->isSubmitted() ? 422 : 200
    ));
  }

  #[Route('/{id}', name: 'app_link_show', methods: ['GET'])]
  public function show(Link $link): Response
  {
    return $this->render('link/show.html.twig', [
      'link' => $link,
    ]);
  }

  #[Route('/{id}/edit', name: 'app_link_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Link $link, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(LinkType::class, $link);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('link/edit.html.twig', [
      'link' => $link,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_link_delete', methods: ['POST'])]
  public function delete(Request $request, Link $link, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $link->getId(), $request->getPayload()->getString('_token'))) {
      $entityManager->remove($link);
      $entityManager->flush();
    }

    return $this->redirectToRoute('app_link_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}/getLinkPass', name: 'app_link_pass', methods: ['GET'])]
  public function pass(Link $link, PasswordManager $passwordManager): Response
  {
    return new Response($passwordManager->decryptPassword($link->getEncData()), Response::HTTP_OK);
  }
}
