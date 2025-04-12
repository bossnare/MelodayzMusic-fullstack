<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommentsRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ODM\Document(collection: 'comments', repositoryClass: CommentsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['comment:read']],
    denormalizationContext: ['groups' => ['comment:write']],
)]
#[Groups(['song:read'])]
#[ODM\HasLifecycleCallbacks]
class Comments
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'comments')]
    private ?User $author = null;

    #[ODM\Field(type: 'string')]
    private ?string $content = null;

    #[ODM\ReferenceOne(targetDocument: Song::class, inversedBy: 'comments')]
    private ?Song $song = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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
