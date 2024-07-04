<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubmissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['submission:read']],
)]
class Submission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("submission:read")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("submission:read")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'submissions')]
    #[Groups("submission:read")]
    private ?Form $form = null;

    /**
     * @var Collection<int, FieldValue>
     */
    #[ORM\OneToMany(targetEntity: FieldValue::class, mappedBy: 'submission')]
    #[Groups("submission:read")]
    private Collection $fieldValues;

    #[ORM\ManyToOne(inversedBy: 'submissions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("submission:read")]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    #[Groups("submission:read")]
    private ?int $number = null;

    #[ORM\Column(nullable: false)]
    #[Groups("submission:read")]
    private ?int $status = 0;

    public function __construct()
    {
        $this->fieldValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): static
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return Collection<int, FieldValue>
     */
    public function getFieldValues(): Collection
    {
        return $this->fieldValues;
    }

    public function addFieldValue(FieldValue $fieldValue): static
    {
        if (!$this->fieldValues->contains($fieldValue)) {
            $this->fieldValues->add($fieldValue);
            $fieldValue->setSubmission($this);
        }

        return $this;
    }

    public function removeFieldValue(FieldValue $fieldValue): static
    {
        if ($this->fieldValues->removeElement($fieldValue)) {
            // set the owning side to null (unless already changed)
            if ($fieldValue->getSubmission() === $this) {
                $fieldValue->setSubmission(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): static
    {
        $this->number = $number;

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
}
