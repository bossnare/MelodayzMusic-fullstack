<?php
//Event Listener manampy custom payload
namespace App\EventListener;

use App\Document\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
// use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event) {
        // maka ilay user avy amin'ny event
        $user = $event->getUser();
        // maka ilay payload avy amin'ny event
        $payload = $event->getData();

        // Manampy payload izay ilaina //eto dia id sy email
        // raha mila manampy payload hafa dia azonao atao ny manampy eto
        // ohatra: $payload['role'] = $user->getRoles();
        if ($user instanceof User) {
            $payload['id'] = $user->getID();
            $payload['username'] = $user->getUserIdentifier();
            $payload['name'] = $user->getUsername();
            $payload['profile'] = $user->getActivateProfilePictures();
            $payload['default'] = $user->getDefaultProfilePictures();
            $payload['email'] = $user->getEmail();
            $event->setData($payload);
        }
    }
}