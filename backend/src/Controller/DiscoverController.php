<?php

namespace App\Controller;

use App\Document\ListeningHistory;
use App\Document\Song;
use App\Repository\ListeningHistoryRepository;
use App\Repository\SongRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DiscoverController extends AbstractController
{

    public function __construct(private DocumentManager $dm, private SongRepository $songRepository, private ListeningHistoryRepository $listeningHistoryRepository) {}

    #[Route('/api/songs/{id}/play', name: 'play', methods: ['POST'])]
    public function playSong(Song $song, Security $security): JsonResponse
    {
        try {
            $player = $security->getUser();

            //verification raha efa anaty nihaino ilay user
            $history = $this->listeningHistoryRepository->findAllListening($player, $song);

            if ($history) {
                return new JsonResponse(['message' => 'Already listened today'], Response::HTTP_OK);
            }

            //raha tsy mbola nihaino androany ny user dia alefa incrdmentation an'ny playCount
            $song->incrementPlayCount();
            $this->dm->persist(new ListeningHistory($player, $song));
            $this->dm->flush();
            return new JsonResponse([
                'message' => 'updated playCount'
            ]);
        } catch (\Exception $error) {
            return new JsonResponse([
                'message' => $error->getMessage()
            ], 500);
        }
    }

    #[Route('/api/songs/{id}/popular', name: 'popular', methods: ['POST'])]
    public function popularSong(): JsonResponse
    {

        // mifidy hira malaza
        $songs = $this->songRepository->findAllPopularSong();

        $songData = [];
        foreach ($songs as $song) {
            $songData[] = [
                'id' => $song->getId(),
                'title' => $song->getTitle(),
                'playCount' => $song->getPlayCount(),
            ];
        }
        return new JsonResponse([
            'songs' => $songData
        ]);
    }
}
