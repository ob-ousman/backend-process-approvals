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
)]
class Workflow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['workflow:read', 'form:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['workflow:read', 'form:read'])]
    private ?string $nom = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'workflows')]
    private Collection $validateurs;

    #[ORM\OneToOne(mappedBy: 'workflow', cascade: ['persist', 'remove'])]
    #[Groups(['workflow:read', 'form:read'])]
    private ?Form $form = null;

    #[ORM\Column]
    #[Groups(['workflow:read', 'form:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->validateurs = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getValidateurs(): Collection
    {
        return $this->validateurs;
    }

    public function addValidateur(User $validateur): static
    {
        if (!$this->validateurs->contains($validateur)) {
            $this->validateurs->add($validateur);
        }

        return $this;
    }

    public function removeValidateur(User $validateur): static
    {
        $this->validateurs->removeElement($validateur);

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
}
