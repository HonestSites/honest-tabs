<?php

  namespace App\Service;

  use App\Entity\User;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

  class PasswordManager
  {
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function hashPassword($user): string
    {
      return $this->hasher->hashPassword(
        $user,
        $user->getPassword()
      );
    }

    public function hashNonUserPassword($object, $plainPassword): string
    {
      $object = new User();
      return $this->hasher->hashPassword(
        $object,
        $plainPassword
      );
    }
  }
