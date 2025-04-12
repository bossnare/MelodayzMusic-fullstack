<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FavoriteRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ODM\Document(collection: 'favorites', repositoryClass: FavoriteRepository::class)]
#[ApiResource]
#[ODM\HasLifecycleCallbacks] //ity no mampandeha ny prePersit sy preUpdate
class Favorite
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'favorites')]
    private ?User $lover = null;

    #[ODM\ReferenceOne(targetDocument: Song::class, inversedBy: 'favorites')]
    private ?Song $song = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLover(): ?User
    {
        return $this->lover;
    }

    public function setLover(?User $lover): static
    {
        $this->lover = $lover;

        return $this;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): static
    {
        $this->song = $song;

        return $this;
    }


       //prePersit sy preUpdate 
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
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
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


}
