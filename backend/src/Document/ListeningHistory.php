<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ListeningHistoryRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


#[ODM\Document(collection: 'listening_history')]
#[ApiResource]
class ListeningHistory
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\ReferenceOne(targetDocument: User::class, inversedBy: 'listeningHistories')]
    private ?User $player = null;

    #[ODM\ReferenceOne(targetDocument: Song::class, inversedBy: 'listeningHistories')]
    private ?Song $song = null;

    #[ODM\Field('date')]
    private ?\DateTimeInterface $playedAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

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

    public function getPlayedAt(): ?\DateTimeInterface
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeInterface $playedAt): static
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    public function __construct(User $player, Song $song) {
        $this->player = $player;
        $this->song = $song;
        $this->playedAt = new \DateTime();
    }
}
