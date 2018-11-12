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
        $user = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user, '123123');
        $user
            ->setRoles([' ROLE_USER', 'ROLE_USER'])
            ->setFirstName('qwe')
            ->setLastName('qwe')
            ->setEmail('qwe@gmail.com')
            ->setPassword($encodedPassword);
        $manager->persist($user);
        $manager->flush();
    }
}
