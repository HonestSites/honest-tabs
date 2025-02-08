<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $categoryName = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: true)]
    private ?bool $allow_sharing = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;

    /**
     * @var Collection<int, LinkCollection>
     */
    #[ORM\OneToMany(targetEntity: LinkCollection::class, mappedBy: 'category')]
    private Collection $linkCollections;

    public function __construct()
    {
        $this->linkCollections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): static
    {
        $this->categoryName = $categoryName;

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
        return $this->allow_sharing;
    }

    public function setAllowSharing(?bool $allow_sharing): static
    {
        $this->allow_sharing = $allow_sharing;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return Collection<int, LinkCollection>
     */
    public function getLinkCollections(): Collection
    {
        return $this->linkCollections;
    }

    public function addLinkCollection(LinkCollection $linkCollection): static
    {
        if (!$this->linkCollections->contains($linkCollection)) {
            $this->linkCollections->add($linkCollection);
            $linkCollection->setCategory($this);
        }

        return $this;
    }

    public function removeLinkCollection(LinkCollection $linkCollection): static
    {
        if ($this->linkCollections->removeElement($linkCollection)) {
            // set the owning side to null (unless already changed)
            if ($linkCollection->getCategory() === $this) {
                $linkCollection->setCategory(null);
            }
        }

        return $this;
    }
}
