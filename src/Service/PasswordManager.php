<?php

  namespace App\Service;

  use App\Entity\User;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

  class PasswordManager
  {
    private string $cipher =  'aes-128-gcm';

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


    public function encryptPassword(  #[\SensitiveParameter] string $plainText): array
    {
      $key = $this->getKey();
      $ivLen = openssl_cipher_iv_length($this->cipher);
      $iv = openssl_random_pseudo_bytes($ivLen);
      $cipherText = openssl_encrypt($plainText, $this->cipher, $key, $options=0, $iv, $tag);
      return [
        'cipherText' => base64_encode($cipherText),
        'iv' => base64_encode($iv),
        'tag' => base64_encode($tag),
        'key' => base64_encode($key),
      ];
    }

    public function decryptPassword($data): string
    {
      $cipherText = base64_decode($data[0]);
      $iv = base64_decode($data[1]);
      $tag = base64_decode($data[2]);
      $key = base64_decode($data[3]);

      return openssl_decrypt($cipherText, $this->cipher, $key, $options=0, $iv, $tag);
    }


    public function getKey(): string
    {
      return base64_encode(time());
    }
  }
