<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $baseUrl = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: true)]
    private ?bool $allowSharing = null;

    #[ORM\ManyToOne(inversedBy: 'link')]
    private ?LinkCollection $linkCollection = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $siteUsername = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $sitePassword = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): static
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function isAllowSharing(): ?bool
    {
        return $this->allowSharing;
    }

    public function setAllowSharing(?bool $allowSharing): static
    {
        $this->allowSharing = $allowSharing;

        return $this;
    }

    public function getLinkCollection(): ?LinkCollection
    {
        return $this->linkCollection;
    }

    public function setLinkCollection(?LinkCollection $linkCollection): static
    {
        $this->linkCollection = $linkCollection;

        return $this;
    }

    public function getSiteUsername(): ?string
    {
        return $this->siteUsername;
    }

    public function setSiteUsername(?string $siteUsername): static
    {
        $this->siteUsername = $siteUsername;

        return $this;
    }

    public function getSitePassword(): ?string
    {
        return $this->sitePassword;
    }

    public function setSitePassword(?string $sitePassword): static
    {
        $this->sitePassword = $sitePassword;

        return $this;
    }
}
