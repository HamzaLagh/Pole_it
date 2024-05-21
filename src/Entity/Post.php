<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $content = null;



    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'posts', targetEntity: VuePosts::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $vues;

    #[ORM\OneToMany(mappedBy: 'posts', targetEntity: Comment::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $comments;


    #[ORM\OneToMany(mappedBy: 'post', targetEntity: ImagesComment::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $imagesComments;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostLike::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $postLikes;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: ImagesPost::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $imagesPosts;




    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->imagesComments = new ArrayCollection();
        $this->vues = new ArrayCollection();
        $this->postLikes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();

        $this->imagesPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPosts($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPosts() === $this) {
                $comment->setPosts(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, ImagesComment>
     */
    public function getImagesComments(): Collection
    {
        return $this->imagesComments;
    }

    /**
     * @return Collection<int, VuePosts>
     */
    public function getVues(): Collection
    {
        return $this->vues;
    }

    public function addVue(VuePosts $vue): self
    {
        if (!$this->vues->contains($vue)) {
            $this->vues->add($vue);
            $vue->setPosts($this);
        }

        return $this;
    }

    public function removeVue(VuePosts $vue): self
    {
        if ($this->vues->removeElement($vue)) {
            // set the owning side to null (unless already changed)
            if ($vue->getPosts() === $this) {
                $vue->setPosts(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, PostLike>
     */
    public function getPostLikes(): Collection
    {
        return $this->postLikes;
    }

    public function addPostLike(PostLike $postLike): self
    {
        if (!$this->postLikes->contains($postLike)) {
            $this->postLikes->add($postLike);
            $postLike->setPost($this);
        }

        return $this;
    }

    public function removePostLike(PostLike $postLike): self
    {
        if ($this->postLikes->removeElement($postLike)) {
            // set the owning side to null (unless already changed)
            if ($postLike->getPost() === $this) {
                $postLike->setPost(null);
            }
        }

        return $this;
    }


    /**
     * isLikedByUser
     *
     * permet de savoir si un article est liké par un user
     * @return boolean
     */
    public function isLikedByUser(User $user)
    {
        foreach ($this->postLikes as $like) {
            if ($like->getUsers() === $user) return true;
        }
        return false;
    }





    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, ImagesPost>
     */
    public function getImagesPosts(): Collection
    {
        return $this->imagesPosts;
    }

    public function addImagesPost(ImagesPost $imagesPost): self
    {
        if (!$this->imagesPosts->contains($imagesPost)) {
            $this->imagesPosts->add($imagesPost);
            $imagesPost->setPost($this);
        }

        return $this;
    }

    public function removeImagesPost(ImagesPost $imagesPost): self
    {
        if ($this->imagesPosts->removeElement($imagesPost)) {
            // set the owning side to null (unless already changed)
            if ($imagesPost->getPost() === $this) {
                $imagesPost->setPost(null);
            }
        }

        return $this;
    }
}
