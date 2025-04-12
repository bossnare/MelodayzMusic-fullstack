<?php

namespace App\Controller;

use App\Document\Comments;
use App\Document\Song;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentController extends AbstractController
{
    public function __construct(private DocumentManager $dm, private Security $security) {}

    #[Route('/api/comments/post/{id}', name: 'post_comments', methods: ['POST'])]
    public function postComment(Request $request, string $id): JsonResponse
    {
        $author = $this->security->getUser();
        try {

            if (!$author) {
                return new JsonResponse([
                    'message' => 'author not found!'
                ], Response::HTTP_FORBIDDEN);
            }

            $song = $this->dm->getRepository(Song::class)->find($id);

            $data = json_decode($request->getContent(), true);
            $content = $data['content'];

            $comment = new Comments();
            $comment->setContent($content);
            $comment->setAuthor($author);
            $comment->setSong($song);

            $this->dm->persist($comment);
            $this->dm->flush();

            // raha 
            return new JsonResponse([
                'message' => 'Envoyé avec succès',
                'new' => $comment,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
