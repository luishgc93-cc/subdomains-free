<?php

namespace App\Entity;

use App\Repository\DomainRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DomainRepository::class)]
class Domain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $subdomainLimitExceeded = null;

    #[ORM\Column]
    private ?int $numberAssociatedSubdomains = null;

    #[ORM\Column]
    private ?bool $isEnabled = null;

    #[ORM\Column]
    private ?bool $isPremium = null;

    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: Subdomain::class)]
    private Collection $subdomains;

    public function __construct()
    {
        $this->subdomains = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isSubdomainLimitExceeded(): ?bool
    {
        return $this->subdomainLimitExceeded;
    }

    public function setSubdomainLimitExceeded(bool $subdomainLimitExceeded): static
    {
        $this->subdomainLimitExceeded = $subdomainLimitExceeded;

        return $this;
    }

    public function getNumberAssociatedSubdomains(): ?int
    {
        return $this->numberAssociatedSubdomains;
    }

    public function setNumberAssociatedSubdomains(int $numberAssociatedSubdomains): static
    {
        $this->numberAssociatedSubdomains = $numberAssociatedSubdomains;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): static
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function isPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(bool $isPremium): static
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    public function getSubdomains(): Collection
    {
        return $this->subdomains;
    }
}
