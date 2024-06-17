<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubmissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
#[ApiResource()]
class Submission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'submissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Form $form = null;

    /**
     * @var Collection<int, FieldValue>
     */
    #[ORM\OneToMany(targetEntity: FieldValue::class, mappedBy: 'submission')]
    private Collection $fieldValues;

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
}
