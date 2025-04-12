<?php

namespace App\Controller;

// Miantso ilay Document User mba hakana methods

use Symfony\Component\HttpFoundation\Response;
use App\Document\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Manao hashing
use Doctrine\ODM\MongoDB\DocumentManager;

final class RegisterController extends AbstractController
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher, private DocumentManager $documentManager) {}

    public function __invoke(Request $request, UserRepository $userRepository): JsonResponse
    {
        // Maka ilay data avy amin'ny request
        $data = json_decode($request->getContent(), true);

        // Maka ilay User class
        $user = new User();
        // Direct mampiditra email sy username azo avy @ $data
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);

        // Manao hashing password mba azo eken'ny Symfony security, mampiasa method hashPassword
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        // Ampidirina ny password efa hashed
        $user->setPassword($hashedPassword);

        // Tokony mampiditra default roles hoan'ny user
        $user->setRoles(['ROLE_USER']);

        // Validation raha misy ao amin'ny Db ilay email
        $repo = $userRepository->findOneBy(['email' => $data['email']]);
        if ($repo) {
            return new JsonResponse(["status" => "emailError", 'error' => 'Email already exists!'], Response::HTTP_BAD_REQUEST);
        }

        // Mampiasa session sy manomboka transaction
        $session = $this->documentManager->getClient()->startSession();
        $session->startTransaction();

        try {
            // Alefaso any amin'ny database
            $this->documentManager->persist($user);
            $this->documentManager->flush();

            // Commit ny transaction raha tsy misy olana
            $session->commitTransaction();

            // Averina json mba ho azon'ny response success amin'ny frontend
            return new JsonResponse(
                ["message" => "User registered successfully!"],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            // Raha misy exception, mamerina rollback
            $session->abortTransaction();
            return new JsonResponse(
                ["status" => "error", "error" => 'Something went wrong!'],
                Response::class::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
