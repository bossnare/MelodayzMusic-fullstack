<?php

namespace App\Controller;

use App\Document\Favorite;
use App\Document\Song;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class FavoriteController extends AbstractController
{
    public function __construct(private DocumentManager $dm, private Security $security) {}
    #[Route('/api/favorites/add', name: 'app_create_favorites', methods: ['POST'])]
    public function addFavorite(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->security->getUser();
        $song = $this->dm->getRepository(Song::class)->find($data['song']);
        try {
            if (!$user || !$song) {
                return new JsonResponse([
                    'message' => 'no user or song found!'
                ], Response::HTTP_FORBIDDEN);
            }

            $favorite = new Favorite();
            $favorite->setLover($user);
            $favorite->setSong($song);

            $this->dm->persist($favorite);
            $this->dm->flush();

            return new JsonResponse([
                'message' => 'favorite'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/favorites/remove/{id}', name: 'app_remove_favorites', methods: ['DELETE'])]
    public function rdmoveFavorite(int $id): JsonResponse
    {
        $favorite = $this->dm->getRepository(Favorite::class)->find($id);
        try {
            if (!$favorite) {
                return new JsonResponse([
                    'message' => 'no favorite for id!'
                ], Response::HTTP_FORBIDDEN);
            }

            $this->dm->remove($favorite);
            $this->dm->flush();

            return new JsonResponse([
                'message' => 'rdmove favorite'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
