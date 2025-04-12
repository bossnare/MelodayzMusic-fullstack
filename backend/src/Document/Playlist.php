<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'playlists')]
#[ApiResource]
// mampiditra ny HasLifecycleCallbacks ho an'ny prePersist sy preUpdate
#[ODM\HasLifecycleCallbacks]
class Playlist
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private ?string $name = null;

    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'playlists')]
    private ?User $author = null;

    /**
     * @var Collection<int, Song>
     */
    #[ODM\ReferenceMany(targetDocument: Song::class, inversedBy: 'playlists')]
    private Collection $song;

    public function __construct()
    {
        $this->song = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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

    /**
     * @return Collection<int, Song>
     */
    public function getSong(): Collection
    {
        return $this->song;
    }

    public function addSong(Song $song): static
    {
        if (!$this->song->contains($song)) {
            $this->song->add($song);
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        $this->song->removeElement($song);

        return $this;
    }

 
}
