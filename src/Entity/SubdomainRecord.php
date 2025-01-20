<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class SubdomainRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $type = null;

    #[ORM\Column(length: 500)]
    private ?string $value = null;

    #[ORM\Column]
    private ?int $ttl = null;

    #[ORM\Column(nullable: true)]
    private ?int $priority = null;

    #[ORM\ManyToOne(targetEntity: Subdomain::class, inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subdomain $subdomain = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    public function setTtl(int $ttl): static
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getSubdomain(): ?Subdomain
    {
        return $this->subdomain;
    }

    public function setSubdomain(?Subdomain $subdomain): static
    {
        $this->subdomain = $subdomain;

        return $this;
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
}
