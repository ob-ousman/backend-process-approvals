<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use App\Controller\CreateSignatureController;
use App\Controller\SignatureController;
use App\Repository\SignatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SignatureRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/signatures/upload',
            controller: SignatureController::class,
            deserialize: false
        ),
        new Get(uriTemplate: '/signatures'),
        new Get(uriTemplate: '/signatures/{id}')
    ],
    normalizationContext: ['groups' => ['signature:read']]
)]
class Signature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['signature:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['signature:read'])]
    private ?string $filename = null;

    private ?File $file = null;

   public function getId()
   {
    return $this->id;
   }

   public function getFilename()
   {
    return $this->filename;
   }

   public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;
        return $this;
    }
}