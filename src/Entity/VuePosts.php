<?php

namespace App\Entity;

use App\Repository\VuePostsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VuePostsRepository::class)]
class VuePosts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('post:read')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('post:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'vues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $posts = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPosts(): ?Post
    {
        return $this->posts;
    }

    public function setPosts(?Post $posts): self
    {
        $this->posts = $posts;

        return $this;
    }
}
