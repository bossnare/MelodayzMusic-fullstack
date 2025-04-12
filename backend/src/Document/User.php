<?php

namespace App\Document;

// ampiana ireto mba ampiasaina amin'ny user account, miantoka ny securité, user && password
use Symfony\Component\Security\Core\User\UserInterface; //ito hoan'ny User identfication
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface; //ito hoan'ny password auth
// mba hanaovana groups read & write hoan'ny api
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Controller\RegisterController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'users', repositoryClass: UserRepository::class)]
#[ODM\HasLifecycleCallbacks] //ity no mampandeha ny prePersit sy preUpdate
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(),
        new Post(
            uriTemplate: '/users/register',
            controller: RegisterController::class,
        ),
        new Put(),
        new Patch(),
        new Delete()
    ]
)]

#[ODM\Index(keys: ['email' => 'asc'], options: ['unique' => true])] // Index ho an'ny email unique
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['read'])]
    #[ODM\Id]
    private ?string $id = null;

    #[Groups(['read', 'write'])]
    #[ODM\Field(type: 'string')]
    #[Assert\NotBlank(message: "Username is required")]
    #[Assert\Length(min: 3, max: 20, minMessage: "Username must be at least 6 characters long")]
    private ?string $username = null;

    #[Groups(['read', 'write'])]
    #[ODM\Field(type: 'string')]
    #[Assert\NotNull(message: "Email is required")]
    #[Assert\Email(message: "Ivalid email format example@gmail.com")]
    private ?string $email = null;

    #[Groups(['write'])]
    #[ODM\Field(type: 'string')]
    #[Assert\NotBlank(message: "Password is required")]
    #[Assert\Length(min: 6, minMessage: "Password must be at least 6 characters long")]
    private ?string $password = null;

    // Default roles ho an'ny mpampiasa; default: ROLE_USER
    #[Groups(['read', 'write'])]
    #[ODM\Field(type: "collection", options: ["default" => []])]
    private array $roles = [];

    #[Groups(['read'])]
    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $createdAt = null;

    #[Groups(['read'])]
    #[ODM\Field(type: 'date')]
    private ?\DateTimeInterface $updatedAt = null;


    /**
     * @var Collection<int, Song>
     */
    #[ODM\ReferenceMany(targetDocument: Song::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    #[Groups(['read'])]
    private Collection $songs;


    //prePersit sy preUpdate 
    #[ODM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ODM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }


    /**
     * @var Collection<int, ProfilePicture>
     */
    #[ODM\ReferenceMany(targetDocument: ProfilePicture::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    #[Groups(['read'])]
    private Collection $profilePictures;

    /**
     * @var Collection<int, Album>
     */
    #[ODM\ReferenceMany(targetDocument: Album::class, mappedBy: 'user')]
    private Collection $albums;

    /**
     * @var Collection<int, Playlist>
     */
    #[ODM\ReferenceMany(targetDocument: Playlist::class, mappedBy: 'author')]
    #[Groups(['read'])]
    private Collection $playlists;

    #[ODM\Field(type: 'string', nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $biographie = null;

    /**
     * @var Collection<int, Comments>
     */
    #[ODM\ReferenceMany(targetDocument: Comments::class, mappedBy: 'author', orphanRemoval: true,  cascade: ['persist', 'remove'])]
    private Collection $comments;

    /**
     * @var Collection<int, Favorite>
     */
    #[ODM\ReferenceMany(targetDocument: Favorite::class, mappedBy: 'lover', orphanRemoval: true,  cascade: ['persist', 'remove'])]
    #[Groups(['read'])]
    private Collection $favorites;

    /**
     * @var Collection<int, ListeningHistory>
     */
    #[ODM\ReferenceMany(targetDocument: ListeningHistory::class, mappedBy: 'player')]
    private Collection $listeningHistories;

    /**
     * @var Collection<int, Activity>
     */
    #[ODM\ReferenceMany(targetDocument: Activity::class, mappedBy: 'user')]
    private Collection $activities;

    public function __construct()
    {
        // Mametraka default role "ROLE_USER"
        // raha misy role hafa dia tsy maintsy anisan'izany ny "ROLE_USER"
        $this->roles = ['ROLE_USER'];
        $this->songs = new ArrayCollection();
        $this->profilePictures = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->listeningHistories = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    // --- Getters & Setters ---

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Ity method ity dia ampiasaina ho an'ny authentication, ary mamerina
     * ny identifier tokana an'ny mpampiasa, izay amin'ity tranga ity dia ny username.
     */
    #[Groups(['read'])]
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
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

    /**
     * Mamerina ny roles ary miantoka fa "ROLE_USER" foana no anisan'izany.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Ampiana ny ROLE_USER raha tsy misy
        // raha tsy misy ROLE_USER dia ampiana
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): static
    {
        $this->biographie = $biographie;

        return $this;
    }


    /**
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): static
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
            $song->setUser($this);
        }
        return $this;
    }

    public function removeSong(Song $song): static
    {
        if ($this->songs->removeElement($song)) {
            if ($song->getUser() === $this) {
                $song->setUser(null);
            }
        }
        return $this;
    }



    // --- Methods required by UserInterface ---

    /**
     * Raha mampiasa algorithm toy ny bcrypt na argon2 ianao, tsy ilaina ny salt.
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Esorina ireo angon-drakitra manan-danja (toy ny credentials plaina) raha tsy ilaina intsony.
     */
    public function eraseCredentials(): void
    {
        // Tsy misy angon-drakitra vonjimaika mila esorina amin'ity tranga ity.
    }



    /**
     * @return Collection<int, ProfilePicture>
     */
    public function getProfilePictures(): Collection
    {
        return $this->profilePictures;
    }

    // maka ilay active
    #[Groups(['read'])]
    public function getActivateProfilePictures(): ?ProfilePicture
    {
        foreach ($this->profilePictures as $picture) {
            //manamarina raha manana state active ilay $picture
            if ($picture->getIsActive()) {
                return $picture;
            }
        }
        return null;
    }

    #[Groups(['read', 'song:read'])]
    public function getDefaultProfilePictures(): ?string
    {

        return 'http://localhost:8000/uploads/default_picture.png';
    }


    public function addProfilePicture(ProfilePicture $profilePicture): static
    {
        if (!$this->profilePictures->contains($profilePicture)) {
            $this->profilePictures->add($profilePicture);
            $profilePicture->setUser($this);
        }

        return $this;
    }

    public function removeProfilePicture(ProfilePicture $profilePicture): static
    {
        if ($this->profilePictures->removeElement($profilePicture)) {
            // set the owning side to null (unless already changed)
            if ($profilePicture->getUser() === $this) {
                $profilePicture->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->setUser($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getUser() === $this) {
                $album->setUser(null);
            }
        }

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
            $playlist->setAuthor($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getAuthor() === $this) {
                $playlist->setAuthor(null);
            }
        }

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
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
            $favorite->setLover($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getLover() === $this) {
                $favorite->setLover(null);
            }
        }

        return $this;
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
            $listeningHistory->setPlayer($this);
        }

        return $this;
    }

    public function removeListeningHistory(ListeningHistory $listeningHistory): static
    {
        if ($this->listeningHistories->removeElement($listeningHistory)) {
            // set the owning side to null (unless already changed)
            if ($listeningHistory->getPlayer() === $this) {
                $listeningHistory->setPlayer(null);
            }
        }

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
            $activity->setUser($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getUser() === $this) {
                $activity->setUser(null);
            }
        }

        return $this;
    }
}
