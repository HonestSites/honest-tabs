<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Form\FormType;
use App\Service\FileManager;
use App\Service\FileUploader;
use App\Service\ImportManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ImportController extends AbstractController
{
  private Request $request;
  public function __construct(
    RequestStack $requestStack,
    private readonly FileManager $fileManager,
    private readonly ImportManager $importManager,
  )
  {
    $this->request = $requestStack->getCurrentRequest();
  }

    #[Route('/import', name: 'app_import')]
    public function index(): Response
    {
        return $this->render('import/index.html.twig', [
            'controller_name' => 'ImportController',
        ]);
    }

  #[Route('/import/bitwarden', name: 'app_import_bitwarden')]
  public function bitwarden(): Response
  {
    $form = $this->createForm(FormType::class);
    $form->handleRequest($this->request);

    if ($form->isSubmitted() && $form->isValid()) {
      $data = $form->getData();
      $results = $this->fileManager->upload($data['file'], ['json']);
      $this->importManager->importBitWarden($results);
      $this->addFlash('success', 'Bitwarden imported successfully.');
      return $this->redirectToRoute('app_home');
    }

    return $this->render('import/index.html.twig', [
      'form' => $form->createView(),
    ]);
  }

}
