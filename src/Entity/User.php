<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('comment:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('comment:read')]
    private ?string $username = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('comment:read')]
    private ?string $token = null;


    #[ORM\Column(type: Types::TEXT)]
    #[Groups('comment:read')]
    private ?string $banner = null;

    #[ORM\Column]
    #[Groups('comment:read')]
    private ?bool $activated = null;

    #[ORM\Column]
    #[Groups('comment:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups('comment:read')]
    private ?bool $isVerified = null;

    #[ORM\Column]
    #[Groups('comment:read')]
    private ?bool $isCompteclosed = null;

    #[ORM\Column(length: 180)]
    #[Groups('comment:read')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $photo = null;

    public function __construct()
    {
        $this->activated = false;
        $this->isVerified = false;
        $this->isCompteclosed = false;
        $this->banner = "Ma banniere";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }


    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * calculAge
     * permet de calculer l'age de l'utilisateur
     * @return int
     */
    public function calculAge($age)
    {
        $aujourdhui = date("Y-m-d");
        $string_value = $age->format('Y-m-d');
        $diff = date_diff(date_create($string_value), date_create($aujourdhui));
        return $diff->format('%y');
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(string $banner): static
    {
        $this->banner = $banner;

        return $this;
    }

    public function isActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): static
    {
        $this->activated = $activated;

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

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isIsCompteclosed(): ?bool
    {
        return $this->isCompteclosed;
    }

    public function setIsCompteclosed(bool $isCompteclosed): static
    {
        $this->isCompteclosed = $isCompteclosed;

        return $this;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }



    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
