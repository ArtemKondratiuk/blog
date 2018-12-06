<?php

namespace App\EntityListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPassword()
        );
        $entity->setPassword($encoded);
    }


}

