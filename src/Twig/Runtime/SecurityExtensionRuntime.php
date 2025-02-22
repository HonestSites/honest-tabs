<?php

namespace App\Twig\Runtime;

use App\Service\PasswordManager;
use Twig\Extension\RuntimeExtensionInterface;

class SecurityExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly PasswordManager $passwordManager)
    {
        // Inject dependencies if needed
    }

    public function decryptText($encData): string
    {
      return $this->passwordManager->decryptPassword($encData);
    }
}
