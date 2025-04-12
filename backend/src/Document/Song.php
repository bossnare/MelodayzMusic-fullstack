<?php

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
// touche perso
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiProperty;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

// use ApiPlatform\Doctrine\Odm\Filter\OrderFilter;
// use ApiPlatform\Metadata\FilterInterface;

use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Controller\SongUploadController;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


#[ODM\Document(collection: 'songs')]
#[ApiResource(
    normalizationContext: ['groups' => ['song:read']],
    denormalizationContext: ['groups' => ['song:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/songs/upload_song',
            controller: SongUploadController::class,
        ),
        new Put(),
        new Patch(),
        new Delete()
    ]
)]
#[ODM\HasLifecycleCallbacks] //ity no mampandeha ny prePersit sy preUpdate
// touche perso
#[Vich\Uploadable]
#[Groups(['read'])]
class Song
{
    #[Groups(['song:read'])]
    #[ODM\Id]
    private ?string $id = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field(type: 'string')]
    private ?string $title = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field(type: 'string',)]
    private ?string $artist = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field(type: 'string',)]
    private ?string $album = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field(type: 'string',)]
    private ?string $genre = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $releaseDate = null;

    // touche perso miandraikitra file upload @Vich
    #[Vich\UploadableField(
        mapping: "songs",
        fileNameProperty: "fileName",
        size: "fileSize"
    )]
    #[Assert\File(
        mimeTypes: ["audio/mpeg", "audio/wav", "audio/ogg"],
        mimeTypesMessage: "Format accepté: MP3, WAV, OGG"
    )]
    private ?File $audioFile = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field(type: 'string')]
    private ?string $fileName = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field]
    private ?int $fileSize = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field]
    private ?string $fileType = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field]
    private ?int $fileDuration = null;

    #[Groups(['song:read'])]
    #[ODM\Field(type: 'date')]
    private ?\DateTime $createdAt = null;

    #[Groups(['song:read'])]
    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;

    #[Groups(['song:read', 'song:write'])]
    #[ODM\Field(type: 'string')]
    private ?string $description = null;

    #[Groups(['song:read'])]
    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'songs')]
    private ?User $user = null;

    /**
     * @var Collection<int, Comments>
     */
    #[ODM\ReferenceMany(targetDocument: Comments::class, mappedBy: 'song', orphanRemoval: true)]
    #[Groups(['song:read'])]
    private Collection $comments;

    /**
     * @var Collection<int, Favorite>
     */
    #[ODM\ReferenceMany(targetDocument: Favorite::class, mappedBy: 'song', orphanRemoval: true)]
    #[Groups(['song:read'])]
    private Collection $favorites;

    #[ODM\Field]
    private ?int $playCount = null;

    /**
     * @var Collection<int, ListeningHistory>
     */
    #[ODM\ReferenceMany(targetDocument: ListeningHistory::class, mappedBy: 'song')]
    private Collection $listeningHistories;

    /**
     * @var Collection<int, Playlist>
     */
    #[ODM\ReferenceMany(targetDocument: Playlist::class, mappedBy: 'song')]
    private Collection $playlists;

    #[ODM\ReferenceOne(targetDocument: SongCover::class, mappedBy: 'song', cascade: ['persist', 'remove'])]
    #[Groups(['song:read'])]
    private ?SongCover $songCover = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ODM\ReferenceMany(targetDocument: Activity::class, mappedBy: 'song')]
    private Collection $activities;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->listeningHistories = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->activities = new ArrayCollection();
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


    // setter et getter audioFile touche perso
    public function setAudioFile(?File $audioFile = null): void
    {

        $this->audioFile = $audioFile;
        if (null !== $audioFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getAudioFile(): ?File
    {
        return $this->audioFile;
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

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(?string $album): static
    {
        $this->album = $album;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeImmutable $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): static
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): static
    {
        $this->fileType = $fileType;

        return $this;
    }

    public function getFileDuration(): ?int
    {
        return $this->fileDuration;
    }

    public function setFileDuration(int $fileDuration): static
    {
        $this->fileDuration = $fileDuration;

        return $this;
    }

    #[Groups(['song:read', 'read'])] //mba ho azo vakina amin'ny get request
    #[ApiProperty(description: 'URL of the file')]
    public function getFileUrl(): ?string
    {
        if (!$this->fileName) {
            return null;
        }

        $schema = $_SERVER['REQUEST_SCHEME'] ?? 'http' . '://' . $_SERVER['HTTP_HOST'] ?? 'localhost:8000';

        return $schema . '/uploads/songs/' . $this->fileName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }



    #[Groups(['song:read', 'read'])]
    public function getDefaultCover(): ?string
    {
        $defaults = ['default_cover', 'default_cover_blue', 'default_cover_gray', 'default_cover_green'];
        $random = $defaults[array_rand($defaults)];

        return 'http://localhost:8000/uploads/' . $random . '.svg';
    }


    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[Groups(['song:read'])]
    public function getUserOwner(): ?array
    {
        // namoronana custom method serialization user tompin'ny song
        return $this->user ? ['id' => $this->user->getId(), 'username' => $this->user->getUsername(), 'activateProfilePicture' => $this->user->getActivateProfilePictures(), 'defaultPicture' => $this->user->getDefaultProfilePictures()] : null;
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

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setSong($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getSong() === $this) {
                $comment->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setSong($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getSong() === $this) {
                $favorite->setSong(null);
            }
        }

        return $this;
    }

    public function getPlayCount(): ?int
    {
        return $this->playCount;
    }

    public function incrementPlayCount(): void
    {
        $this->playCount++;
    }

    /**
     * @return Collection<int, ListeningHistory>
     */
    public function getListeningHistories(): Collection
    {
        return $this->listeningHistories;
    }

    public function addListeningHistory(ListeningHistory $listeningHistory): static
    {
        if (!$this->listeningHistories->contains($listeningHistory)) {
            $this->listeningHistories->add($listeningHistory);
            $listeningHistory->setSong($this);
        }

        return $this;
    }

    public function removeListeningHistory(ListeningHistory $listeningHistory): static
    {
        if ($this->listeningHistories->removeElement($listeningHistory)) {
            // set the owning side to null (unless already changed)
            if ($listeningHistory->getSong() === $this) {
                $listeningHistory->setSong(null);
            }
        }

        return $this;
    }

    public function setPlayCount(?int $playCount): static
    {
        $this->playCount = $playCount;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->addSong($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removeSong($this);
        }

        return $this;
    }

    public function getSongCover(): ?SongCover
    {
        return $this->songCover;
    }

    public function setSongCover(SongCover $songCover): static
    {
        // set the owning side of the relation if necessary
        if ($songCover->getSong() !== $this) {
            $songCover->setSong($this);
        }

        $this->songCover = $songCover;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setSong($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getSong() === $this) {
                $activity->setSong(null);
            }
        }

        return $this;
    }
}
