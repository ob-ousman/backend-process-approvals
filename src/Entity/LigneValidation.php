<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LigneValidationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LigneValidationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['ligneValidation:read']],
)]
class LigneValidation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?int $numero = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?int $status = null;

    #[ORM\Column(length: 255)]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?string $action = null;

    #[ORM\Column(length: 255)]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?string $destinataire = null;

    #[ORM\Column]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'ligneValidation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["ligneValidation:read", "submission:read"])]
    private ?Validation $validation = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["ligneValidation:read", "submission:read", "validation:read"])]
    private ?\DateTimeImmutable $receivedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getValidation(): ?Validation
    {
        return $this->validation;
    }

    public function setValidation(?Validation $validation): static
    {
        $this->validation = $validation;

        return $this;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(\DateTimeImmutable $receivedAt): static
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }
}
