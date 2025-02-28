<?php

namespace App\Controller;

use App\Entity\Link;
use App\Service\PasswordManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ajax')]
final class AjaxController extends AbstractController
{
  #[Route('/{id}/getLinkPass', name: 'ajax_link_pass', methods: ['GET'])]
  public function pass(Link $link, PasswordManager $passwordManager): Response
  {
    return new Response($passwordManager->decryptPassword($link->getEncData()), Response::HTTP_OK);
  }
}
