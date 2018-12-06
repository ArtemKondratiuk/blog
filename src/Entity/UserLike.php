<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_like")
 */
class UserLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $like;

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
    public function getLike()
    {
        return $this->like;
    }

    /**
     * @param mixed $like
     * @return UserLike
     */
    public function setLike($like): self
    {
        $this->like = $like;

        return $this;
    }
}
