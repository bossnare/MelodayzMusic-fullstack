<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'albums', repositoryClass: AlbumRepository::class)]
#[ApiResource]
class Album
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private ?string $title = null;

    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'albums')]
    private ?User $user = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, AlbumSong>
     */
    #[ODM\ReferenceMany(targetDocument: AlbumSong::class, mappedBy: 'album')]
    private Collection $albumSongs;

    public function __construct()
    {
        $this->albumSongs = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    /**
     * @return Collection<int, AlbumSong>
     */
    public function getAlbumSongs(): Collection
    {
        return $this->albumSongs;
    }

    public function addAlbumSong(AlbumSong $albumSong): static
    {
        if (!$this->albumSongs->contains($albumSong)) {
            $this->albumSongs->add($albumSong);
            $albumSong->setAlbum($this);
        }

        return $this;
    }

    public function removeAlbumSong(AlbumSong $albumSong): static
    {
        if ($this->albumSongs->removeElement($albumSong)) {
            // set the owning side to null (unless already changed)
            if ($albumSong->getAlbum() === $this) {
                $albumSong->setAlbum(null);
            }
        }

        return $this;
    }
}
