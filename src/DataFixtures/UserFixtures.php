<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user1, '123123');
        $user1
            ->setFirstName('admin')
            ->setLastName('admin')
            ->setEmail('admin@gmail.com')
            ->setPassword($encodedPassword)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user1);
        $user2 = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user2, '123123');
        $user2
            ->setFirstName('reader')
            ->setLastName('reader')
            ->setEmail('reader@gmail.com')
            ->setPassword($encodedPassword)
            ->setRoles(['ROLE_READER']);
        $manager->persist($user2);
        $user3 = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user3, '123123');
        $user3
            ->setFirstName('bloger')
            ->setLastName('bloger')
            ->setEmail('bloger@gmail.com')
            ->setPassword($encodedPassword)
            ->setRoles(['ROLE_BLOGER']);
        $manager->persist($user3);

        $manager->flush();
    }
}
