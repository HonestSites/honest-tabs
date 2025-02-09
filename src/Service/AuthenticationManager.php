<?php

  namespace App\Service;

  use Symfony\Bundle\SecurityBundle\Security;

  class AuthenticationManager
  {
    public function __construct(
      private readonly Security $security
    )
    {}

    public function getUser()
    {
      return $this->security->getUser();
    }
  }