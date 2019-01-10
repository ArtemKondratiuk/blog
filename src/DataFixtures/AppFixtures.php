<?php


namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
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
//            ->setPassword('123456')
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user1);
        $user2 = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user2, '123123');
        $user2
            ->setFirstName('reader')
            ->setLastName('reader')
            ->setEmail('reader@gmail.com')
            ->setPassword($encodedPassword)
//            ->setPassword('123456')
            ->setRoles(['ROLE_READER']);
        $manager->persist($user2);
        $user3 = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user3, '123123');
        $user3
            ->setFirstName('bloger')
            ->setLastName('bloger')
            ->setEmail('bloger@gmail.com')
            ->setPassword($encodedPassword)
//            ->setPassword('123456')
            ->setRoles(['ROLE_BLOGER']);
        $manager->persist($user3);

        for($i=0; $i<20; $i++) {
            $article =new Article();
            $images = new Image();
            $images->setFileName(('symfony4.png'));
            $images->setPath('/build/images/symfony4.png');
            $images->setArticle($article);
            $manager->persist($images);
            $article->setTitle('A day with Symfony4 №' . $i);
//            $article->addImage($images);
            $article->setText('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify 
            denim vel ports.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut velocity magna. 
            Etiam vehicula nunc non leo hendrerit commodo. Vestibulum vulputate mauris eget erat congue dapibus 
            imperdiet justo scelerisque. Nulla consectetur tempus nisl vitae viverra. Cras el mauris eget erat 
            congue dapibus imperdiet justo scelerisque. Nulla consectetur tempus nisl vitae viverra. Cras elementum 
            molestie vestibulum. Morbi id quam nisl. Praesent hendrerit, orci sed elementum lobortis, justo mauris 
            lacinia libero, non facilisis purus ipsum non mi. Aliquam sollicitudin, augue id vestibulum iaculis, sem 
            lectus convallis nunc, vel scelerisque lorem tortor ac nunc. Donec pharetra eleifend enim vel porta.'.$i);
            $article->setAuthor($user1);
            $tag = new Tag();
            $tag->setName('some tag'.$i);
            $manager->persist($tag);
            $article->addTag($tag);
            $article->setPublish(true);

            $manager->persist($article);

            $comment = new Comment();
            $comment->setText('some comment'.$i);
            $comment->setAuthor($user2);
            $comment->setArticle($article);
            $manager->persist($comment);
        }
        $manager->flush();

    }



}

