<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LigneWorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LigneWorkflowRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['ligneWorkflow:read']],
    denormalizationContext: ['groups' => ['ligneWorkflow:write']],
)]
class LigneWorkflow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['workflow:read','ligneWorkflow:write'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['workflow:read','ligneWorkflow:write'])]
    private ?int $numero = null;

    #[ORM\Column]
    #[Groups(['workflow:read','ligneWorkflow:write'])]
    private ?int $typeDestinataire = null;

    #[ORM\Column]
    #[Groups(['workflow:read','ligneWorkflow:write'])]
    private ?int $action = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['workflow:read','ligneWorkflow:write'])]
    private ?string $destinataire = null;

    /**
     * @var Collection<int, DestinataireDynamique>
     */
    #[ORM\OneToMany(targetEntity: DestinataireDynamique::class, mappedBy: 'ligneWorkflow')]
    #[Groups(['workflow:read','ligneWorkflow:write'])]
    private Collection $destinataireDynamiques;

    #[ORM\ManyToOne(inversedBy: 'ligneWorkflows')]
    private ?Workflow $workflow = null;

    public function __construct()
    {
        $this->destinataireDynamiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTypeDestinataire(): ?int
    {
        return $this->typeDestinataire;
    }

    public function setTypeDestinataire(int $typeDestinataire): static
    {
        $this->typeDestinataire = $typeDestinataire;

        return $this;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(int $action): static
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

    /**
     * @return Collection<int, DestinataireDynamique>
     */
    public function getDestinataireDynamiques(): Collection
    {
        return $this->destinataireDynamiques;
    }

    public function addDestinataireDynamiques(DestinataireDynamique $destinataireDynamiques): static
    {
        if (!$this->destinataireDynamiques->contains($destinataireDynamiques)) {
            $this->destinataireDynamiques->add($destinataireDynamiques);
            $destinataireDynamiques->setLigneWorkflow($this);
        }

        return $this;
    }

    public function removeDestinataireDynamiques(DestinataireDynamique $destinataireDynamiques): static
    {
        if ($this->destinataireDynamiques->removeElement($destinataireDynamiques)) {
            // set the owning side to null (unless already changed)
            if ($destinataireDynamiques->getLigneWorkflow() === $this) {
                $destinataireDynamiques->setLigneWorkflow(null);
            }
        }

        return $this;
    }

    public function getWorkflow(): ?Workflow
    {
        return $this->workflow;
    }

    public function setWorkflow(?Workflow $workflow): static
    {
        $this->workflow = $workflow;

        return $this;
    }
}
