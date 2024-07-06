<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WorkflowRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['workflow:read']],
    denormalizationContext: ['groups' => ['workflow:write']],
)]
class Workflow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['workflow:read', 'form:read', 'workflow:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['workflow:read', 'form:read', 'workflow:write'])]
    private ?string $nom = null;

    #[ORM\OneToOne(mappedBy: 'workflow', cascade: ['persist', 'remove'])]
    #[Groups(['workflow:read', 'form:read', 'workflow:write'])]
    private ?Form $form = null;

    #[ORM\Column]
    #[Groups(['workflow:read', 'form:read', 'workflow:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, LigneWorkflow>
     */
    #[ORM\OneToMany(targetEntity: LigneWorkflow::class, mappedBy: 'workflow')]
    #[Groups(['workflow:read', 'form:read', 'workflow:write'])]
    private Collection $ligneWorkflows;

    public function __construct()
    {
        $this->ligneWorkflows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(Form $form): static
    {
        // set the owning side of the relation if necessary
        if ($form->getWorkflow() !== $this) {
            $form->setWorkflow($this);
        }

        $this->form = $form;

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

    /**
     * @return Collection<int, LigneWorkflow>
     */
    public function getLigneWorkflows(): Collection
    {
        return $this->ligneWorkflows;
    }

    public function addLigneWorkflow(LigneWorkflow $ligneWorkflow): static
    {
        if (!$this->ligneWorkflows->contains($ligneWorkflow)) {
            $this->ligneWorkflows->add($ligneWorkflow);
            $ligneWorkflow->setWorkflow($this);
        }

        return $this;
    }

    public function removeLigneWorkflow(LigneWorkflow $ligneWorkflow): static
    {
        if ($this->ligneWorkflows->removeElement($ligneWorkflow)) {
            // set the owning side to null (unless already changed)
            if ($ligneWorkflow->getWorkflow() === $this) {
                $ligneWorkflow->setWorkflow(null);
            }
        }

        return $this;
    }

}
