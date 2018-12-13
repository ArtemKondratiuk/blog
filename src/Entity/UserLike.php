<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserLikeRepository")
 */
class UserLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userLikes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="userLikes")
     */
    private $article;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLikes(): ?bool
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     * @return UserLike
     */
    public function setLikes(?bool $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return UserLike
     */
    public function setUser($user): self
    {
        $this->user = $user;

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
     * @return UserLike
     */
    public function setArticle($article): self
    {
        $this->article = $article;

        return $this;
    }

    public function __toString() {
        return $this->likes;
    }



}
