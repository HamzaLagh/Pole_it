<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $posts = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: ImagesComment::class, orphanRemoval: true)]
    private Collection $imagesComments;

    public function __construct()
    {
        $this->imagesComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getPosts(): ?Post
    {
        return $this->posts;
    }

    public function setPosts(?Post $posts): static
    {
        $this->posts = $posts;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, ImagesComment>
     */
    public function getImagesComments(): Collection
    {
        return $this->imagesComments;
    }

    public function addImagesComment(ImagesComment $imagesComment): self
    {
        if (!$this->imagesComments->contains($imagesComment)) {
            $this->imagesComments->add($imagesComment);
            $imagesComment->setComment($this);
        }

        return $this;
    }

    public function removeImagesComment(ImagesComment $imagesComment): self
    {
        if ($this->imagesComments->removeElement($imagesComment)) {
            // set the owning side to null (unless already changed)
            if ($imagesComment->getComment() === $this) {
                $imagesComment->setComment(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->content; //or anything else
    }
}
