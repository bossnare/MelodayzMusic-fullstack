<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ActivityRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'activity', repositoryClass: ActivityRepository::class)]
#[ODM\HasLifecycleCallbacks] //ity no mampandeha ny prePersit sy preUpdate
#[ApiResource]
class Activity
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private ?string $activityType = null;

    #[ODM\ReferenceOne(targetDocument: Activity::class, inversedBy: 'activities')]
    private ?User $user = null;

    #[ODM\ReferenceOne(targetDocument: Song::class, inversedBy: 'activities')]
    private ?Song $song = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getActivityType(): ?string
    {
        return $this->activityType;
    }

    public function setActivityType(?string $activityType): static
    {
        $this->activityType = $activityType;

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
}
