<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AlbumSongRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'album_songs', repositoryClass: AlbumSongRepository::class)]
#[ApiResource]
class AlbumSong
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private ?string $tubeName = null;

    #[ODM\Field]
    private ?int $tubeSize = null;

    #[ODM\Field(type: 'string')]
    private ?string $tubeType = null;

    #[ODM\Field]
    private ?int $tubeDuration = null;

    #[ODM\ReferenceOne(targetDocument: Album::class, inversedBy: 'albumSongs')]
    private ?Album $album = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTubeName(): ?string
    {
        return $this->tubeName;
    }

    public function setTubeName(string $tubeName): static
    {
        $this->tubeName = $tubeName;

        return $this;
    }

    public function getTubeSize(): ?int
    {
        return $this->tubeSize;
    }

    public function setTubeSize(?int $tubeSize): static
    {
        $this->tubeSize = $tubeSize;

        return $this;
    }

    public function getTubeType(): ?string
    {
        return $this->tubeType;
    }

    public function setTubeType(?string $tubeType): static
    {
        $this->tubeType = $tubeType;

        return $this;
    }

    public function getTubeDuration(): ?int
    {
        return $this->tubeDuration;
    }

    public function setTubeDuration(?int $tubeDuration): static
    {
        $this->tubeDuration = $tubeDuration;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): static
    {
        $this->album = $album;

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
}
