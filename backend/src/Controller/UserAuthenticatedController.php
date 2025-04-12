<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\SerializerInterface; 

final class UserAuthenticatedController extends AbstractController
{
    #[Route('/api/moi', name: 'api_moi', methods: ['GET'])]
    public function me(SerializerInterface $serializer, Security $security): JsonResponse
    {
        // maka ilay user avy amin'ny security
        $user = $security->getUser();

        try {
            // raha tsy misy ilay user dia averina ny 401 Unauthorized
            if (!$user) {
                return new JsonResponse(['message' => 'Unauthorized'], 401);
            }
            // serialize the user object to JSON
            return new JsonResponse($serializer->serialize($user, 'json', ['groups' => ['read']]), 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
}
