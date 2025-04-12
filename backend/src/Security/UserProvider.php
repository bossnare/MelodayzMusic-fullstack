<?php

namespace App\Security;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserProvider implements UserProviderInterface
{

    // mampiditra ny DocumentManager ho an'ny MongoDB
    public function __construct(private DocumentManager $dm) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        
      
        // maka ilay identifier ho email
        // raha mila id dia azonao atao ny manova ny query
        $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $identifier]);

        // raha tsy hita ilay user dia throw exception
        if (!$user) {
            throw new UserNotFoundException("User not found.");
        }

        // raha hita ilay user dia averina
        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        // miverina ny user raha mbola mitovy ny class
        if (!$user instanceof User) {
            throw new UnsupportedUserException("Invalid user class.");
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        // manamarina raha mitovy ny class amin'ny User
        // raha mitovy dia miverina true raha tsy izany dia false
        return $class === User::class;
    }
}
