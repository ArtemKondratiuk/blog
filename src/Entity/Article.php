<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(name="article")
 */
class Article
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $text;

//    /**
//     * @var \DateTime
//     *
//     * @ORM\Column(type="datetime")
//     * @Assert\Type('\DateTime')
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

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",
     *     mappedBy="article",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     *
     */
    private $comments;

    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="aricle_tag")
     * @ORM\OrderBy({"name": "ASC"})
     * @Assert\Count(max="4", maxMessage="post.too_many_tags")
     */
    private $tags;

    public function __construct()

    {
//        $this->publishedAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Article
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
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
     * @return  Article
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return ArrayCollection|Comment[]
     */
    public function getComments(): ArrayCollection
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     * @return Article
     */
    public function setComments($comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag[]|ArrayCollection $tags
     * @return Article
     */
    public function setTags($tags): self
    {
        $this->tags = $tags;

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
//     * @return Article
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

//    /**
//     * @param User $author
//     * @return Article
//     */
//    public function setAuthor(User $author): self
//    {
//        $this->author = $author;
//
//        return $this;
//    }

    public function addTag(?Tag ...$tags): void
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags->add($tag);
            }
        }
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

}
