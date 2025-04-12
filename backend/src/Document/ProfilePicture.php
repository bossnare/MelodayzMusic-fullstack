<?php

namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Controller\ProfilePictureController;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProfilePictureRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'profile_pictures')]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
            uriTemplate: '/profilePictures/upload_profile_picture',
            controller: ProfilePictureController::class,
        ),
        new Put(),
        new Patch(),
        new Delete()
    ]
)]
#[ODM\HasLifecycleCallbacks] //ity no mampandeha ny prePersit sy preUpdate
// touche perso
#[Vich\Uploadable]
class ProfilePicture
{
    #[ODM\Id]
    private ?string $id = null;

    #[Vich\UploadableField(
        mapping: "profile_pictures",
        fileNameProperty: "pictureName",
        size: "pictureSize"
    )]
    #[Assert\File(
        mimeTypes: ["image/jpeg", "image/png", "image/webp"],
        mimeTypesMessage: "Format accepté: JPG, PNG, WEBP"
    )]
    private ?File $pictureFile = null;

    #[ODM\Field(type: 'string')]
    private ?string $pictureName = null;

    #[ODM\Field]
    private ?int $pictureSize = null;

    #[ODM\Field(type: 'string')]
    private ?string $pictureType = null;

    #[Groups('read')]
    #[ODM\Field]
    private ?bool $isActive = false;

    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'profilePictures')]
    private ?User $user = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setPictureFile(?File $pictureFile = null): void
    {

        $this->pictureFile = $pictureFile;
        if (null !== $pictureFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }


    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    public function setPictureName(string $pictureName): static
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    public function getPictureSize(): ?int
    {
        return $this->pictureSize;
    }

    public function setPictureSize(?int $pictureSize): static
    {
        $this->pictureSize = $pictureSize;

        return $this;
    }

    public function getPictureType(): ?string
    {
        return $this->pictureType;
    }

    public function setPictureType(?string $pictureType): static
    {
        $this->pictureType = $pictureType;

        return $this;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    //manao false izay rehetra tsy active
    public function deactiveActivePicture() {
        foreach($this->user->getProfilePictures() as $pictures) {
            if ($pictures !== $this && $pictures->getIsActive()) {
                $pictures->setIsActive(false);
            }
        }
    }


    #[Groups(['read', 'song:read'])] //mba ho azo vakina amin'ny get request
    public function getPictureUrl(): ?string
    {
        if (!$this->pictureName) {
            return null;
        }

        $schema = $_SERVER['REQUEST_SCHEME'] ?? 'http' . '://' . $_SERVER['HTTP_HOST'] ?? 'localhost:8000';

        return $schema . '/uploads/profiles/' . $this->pictureName;
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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }
}
