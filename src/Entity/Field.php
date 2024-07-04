<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
#[ApiResource()]
#[ApiProperty(identifier: true)]
class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["form:read", "submission:read", "fieldValue:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["form:read", "submission:read", "fieldValue:read"])]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    #[Groups(["form:read", "submission:read", "fieldValue:read"])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(["form:read", "submission:read", "fieldValue:read"])]
    private ?bool $required = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["form:read", "submission:read", "fieldValue:read"])]
    private ?array $options = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    private ?Form $form = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
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

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): static
    {
        $this->required = $required;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): static
    {
        $this->options = $options;

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
}
