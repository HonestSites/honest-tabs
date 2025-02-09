<?php

  namespace App\Twig\Runtime;

  use Twig\Extension\RuntimeExtensionInterface;

  class FileExtensionRuntime implements RuntimeExtensionInterface
  {
    public function __construct()
    {
      // Inject dependencies if needed
    }

    public function getPath($curPath): bool|string
    {
      return dirname($curPath);
    }
  }
