<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @UniqueEntity("email", message="Email already taken")
 */
//@ORM\EntityListeners({"App\EntityListener\UserListener"})
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=191, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
//        $this->roles = ['ROLE_USER'];
        $this->roles = ['ROLE_ADMIN'];
//        $this->roles = ['ROLE_BLOGER'];
        $this->roles = ['ROLE_READER'];
        $this->like = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 6,
     *     max = 20,
     *     minMessage = "Your password must be at least {{ limit }} characters long",
     *     maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;

    /**
     * @var UserLike[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\UserLike", cascade={"persist"})
     * @ORM\JoinTable(name="likes")
     */
    private $like;


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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;

    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return UserLike[]|ArrayCollection
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * @param UserLike[]|ArrayCollection $like
     * @return User
     */
    public function setLike($like): self
    {
        $this->like = $like;

        return $this;
    }

    public function addLike(UserLike $like): self
    {
        if (!$this->like->contains($like)) {
            $this->like[] = $like;
        }

        return $this;
    }

    public function removeLike(UserLike $like): self
    {
        if ($this->like->contains($like)) {
            $this->like->removeElement($like);
        }

        return $this;
    }

}
