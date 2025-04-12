<?php

namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SongCoverRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Controller\SongUploadController;
use Doctrine\DBAL\Types\Types;

#[ODM\Document(collection: 'song_covers', repositoryClass: SongCoverRepository::class)]
#[Vich\Uploadable]
#[ODM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/songCovers/upload',
            controller: SongUploadController::class,
        ),
        new Put(),
        new Patch(),
        new Delete()
    ]
)]
class SongCover
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private ?string $coverName = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;

    #[Vich\UploadableField(
        mapping: "covers",
        fileNameProperty: "coverName",
    )]
    #[Assert\File(
        mimeTypes: ["image/jpeg", "image/png", "image/webp"],
        mimeTypesMessage: "Format accepté: JPG, PNG, WEBP"
    )]
    private ?File $coverFile = null;

    #[ODM\ReferenceOne(targetDocument: Song::class, inversedBy: 'songCover', cascade: ['persist', 'remove'])]
    private ?Song $song = null;


    #[Groups(['song:read', 'read'])] //mba ho azo vakina amin'ny get request
    public function getCoverUrl(): ?string
    {
        if (!$this->coverName) {
            return null;
        }

        $schema = $_SERVER['REQUEST_SCHEME'] ?? 'http' . '://' . $_SERVER['HTTP_HOST'] ?? 'localhost:8000';

        return $schema . '/uploads/covers/' . $this->coverName;
    }



    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCoverName(): ?string
    {
        return $this->coverName;
    }

    public function setCoverName(string $coverName): static
    {
        $this->coverName = $coverName;

        return $this;
    }

    public function setCoverFile(?File $coverFile = null): void
    {

        $this->coverFile = $coverFile;
        if (null !== $coverFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ODM\PrePersist]
    public function onCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ODM\PreUpdate]
    public function onUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCoverFile(): ?File
    {
        return $this->coverFile;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(Song $song): static
    {
        $this->song = $song;

        return $this;
    }

    
}
