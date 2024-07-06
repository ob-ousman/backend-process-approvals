<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ValidationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ValidationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['validation:read']],
)]
class Validation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("validation:read", "submission:read")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("validation:read", "submission:read")]
    private ?int $status = null;

    #[ORM\OneToOne(inversedBy: 'validation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("validation:read")]
    private ?Submission $submission = null;

    /**
     * @var Collection<int, LigneValidation>
     */
    #[ORM\OneToMany(targetEntity: LigneValidation::class, mappedBy: 'validation')]
    #[Groups("validation:read", "submission:read")]
    private Collection $ligneValidation;

    public function __construct()
    {
        $this->ligneValidation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(Submission $submission): static
    {
        $this->submission = $submission;

        return $this;
    }

    /**
     * @return Collection<int, LigneValidation>
     */
    public function getLigneValidation(): Collection
    {
        return $this->ligneValidation;
    }

    public function addLigneValidation(LigneValidation $ligneValidation): static
    {
        if (!$this->ligneValidation->contains($ligneValidation)) {
            $this->ligneValidation->add($ligneValidation);
            $ligneValidation->setValidation($this);
        }

        return $this;
    }

    public function removeLigneValidation(LigneValidation $ligneValidation): static
    {
        if ($this->ligneValidation->removeElement($ligneValidation)) {
            // set the owning side to null (unless already changed)
            if ($ligneValidation->getValidation() === $this) {
                $ligneValidation->setValidation(null);
            }
        }

        return $this;
    }
}
