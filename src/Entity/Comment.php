<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

//    /**
//     * @var \DateTime
//     *
//     * @ORM\Column(type="datetime")
//     * @Assert\Type("\DateTime")
//     * @Assert\NotBlank()
//     */
//    private $publishedAt;

//    /**
//     * @var User
//     *
//     * @ORM\ManyToOne(targetEntity="App\Entity\User")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $author;

//    public function __construct()
//    {
//        $this->publishedAt = new \DateTime();
//    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Comment
     */
    public function setText($text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     * @return Comment
     */
    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

//    /**
//     * @return \DateTime
//     */
//    public function getPublishedAt(): \DateTime
//    {
//        return $this->publishedAt;
//    }
//
//    /**
//     * @param \DateTime $publishedAt
//     * @return Comment
//     */
//    public function setPublishedAt(\DateTime $publishedAt): self
//    {
//        $this->publishedAt = $publishedAt;
//
//        return $this;
//    }

//    /**
//     * @return User
//     */
//    public function getAuthor(): User
//    {
//        return $this->author;
//    }
//
//    /**
//     * @param User $author
//     * @return Comment
//     */
//    public function setAuthor(User $author): self
//    {
//        $this->author = $author;
//
//        return $this;
//    }



}
