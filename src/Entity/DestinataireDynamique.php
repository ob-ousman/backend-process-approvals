<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DestinataireDynamiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DestinataireDynamiqueRepository::class)]
#[ApiResource()]
class DestinataireDynamique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['workflow:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['workflow:read'])]
    private ?string $logique = null;

    #[ORM\Column(length: 255)]
    #[Groups(['workflow:read'])]
    private ?string $valeur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['workflow:read'])]
    private ?Field $field = null;

    #[ORM\Column(length: 255)]
    #[Groups(['workflow:read'])]
    private ?string $destinataire = null;

    #[ORM\ManyToOne(inversedBy: 'destinataireDynamiques')]
    private ?LigneWorkflow $ligneWorkflow = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogique(): ?string
    {
        return $this->logique;
    }

    public function setLogique(string $logique): static
    {
        $this->logique = $logique;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField(?Field $field): static
    {
        $this->field = $field;

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

    public function getLigneWorkflow(): ?LigneWorkflow
    {
        return $this->ligneWorkflow;
    }

    public function setLigneWorkflow(?LigneWorkflow $ligneWorkflow): static
    {
        $this->ligneWorkflow = $ligneWorkflow;

        return $this;
    }
}
