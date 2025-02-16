<?php

namespace App\Entity;

use App\Repository\LinkCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkCollectionRepository::class)]
class LinkCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $collectionName = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: true)]
    private ?bool $allowSharing = null;

    #[ORM\ManyToOne(inversedBy: 'linkCollections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * @var Collection<int, Link>
     */
    #[ORM\OneToMany(targetEntity: Link::class, mappedBy: 'linkCollection')]
    private Collection $link;

    public function __construct()
    {
        $this->link = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCollectionName(): ?string
    {
        return $this->collectionName;
    }

    public function setCollectionName(string $collectionName): static
    {
        $this->collectionName = $collectionName;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Link>
     */
    public function getLink(): Collection
    {
        return $this->link;
    }

    public function addLink(Link $link): static
    {
        if (!$this->link->contains($link)) {
            $this->link->add($link);
            $link->setLinkCollection($this);
        }

        return $this;
    }

    public function removeLink(Link $link): static
    {
        if ($this->link->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getLinkCollection() === $this) {
                $link->setLinkCollection(null);
            }
        }

        return $this;
    }
}
