<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ImportController extends AbstractController
{
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
    return $this->render('import/index.html.twig', [
      'controller_name' => 'Bitwarden',
    ]);
  }

}
