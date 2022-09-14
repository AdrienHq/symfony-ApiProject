<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
//        'get' => ['security' => 'is_granted("ROLE_USER")'],
//        'post' => [
//            'security' => 'is_granted("IS_AUTHENTICATED_ANONYMOUSLY")'],
//            'validation_groups' = ["Default", "create"]
        'get',
        'post',
    ],
    itemOperations: [
        "get"=>['security' => 'is_granted("ROLE_USER")'],
        "put"=>['security' => 'is_granted("ROLE_USER") and object == user'],
        "delete"=>['security' => 'is_granted("ROLE_ADMIN")']
    ],
    denormalizationContext: ["groups" => ['user:write']],
    formats: ['json', 'xml', 'jsonld', 'csv' => ['text/csv']],
    normalizationContext: ["groups" => ['user:read']]
)]
#[ApiFilter(PropertyFilter::class)]
#[UniqueEntity(fields: ["username"])]
#[UniqueEntity(fields: ["email"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["user:read", "user:write"])]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(["admin:write"])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(["user:read"])]
    private ?string $password = null;

    #[Groups(["user:write"])]
    #[SerializedName('password')]
    #[Assert\NotBlank(groups: ["create"])]
    private string $plainPassword;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(["user:read", "user:write", "book:item:get"])]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Books::class)]
    #[Groups(["user:write"])]
    #[Assert\Valid]
    #[ApiSubresource]
    private Collection $books;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $phoneNumber = null;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    #[Groups(["user:read"])]
    #[SerializedName('books')]
    public function getPublishedBooks(): Collection
    {
        return $this->books->filter(function(Books $books){
            return $books->getIsPublished();
        });
    }

    public function addBook(Books $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setOwner($this);
        }

        return $this;
    }

    public function removeBook(Books $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getOwner() === $this) {
                $book->setOwner(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
