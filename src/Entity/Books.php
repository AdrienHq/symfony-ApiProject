<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\BooksRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => 'partial',
    'description' => 'partial',
    'owner' => 'exact',
    'owner.username' => 'partial'
])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security' => 'is_granted("ROLE_USER")'],
    ],
    itemOperations: [
        "get"=>[
            'normalization_context' => ['groups' => ['book:read', 'book:item:get']]
        ],
        'put' => [
            "security" => "is_granted('BOOKS_EDIT', object)",
            "security_message" => "Only the creator can edit a book.",
        ],
        'delete' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
    shortName: "book",
    attributes: [
        "pagination_items_per_page" => 10
    ],
    formats: ['json', 'xml', 'jsonld', 'csv' => ['text/csv']],
)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["book:read", "book:write", "user:read", "user:write"])]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The title contains not enough character. Minimum 2',
        maxMessage: 'The title contains to much character',
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["book:read", "user:read"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["book:read", "book:write"])]
    private ?int $numberOfPages = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOfRelease = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateDateOfRelease = null;

    #[ORM\Column(length: 255)]
    #[Groups(["book:read", "book:write"])]
    #[Assert\NotBlank]
    private ?string $author = null;

    #[ORM\Column]
    #[Groups(["book:read", "book:write"])]
    private ?bool $isPublished = false;

    #[ORM\Column(nullable: true)]
    #[Groups(["book:read", "book:write", "user:read", "user:write"])]
    #[Assert\NotBlank]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[Groups(["book:read", "book:write"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?User $owner = null;

    public function __construct()
    {
        $this->dateOfRelease = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        if(strlen($this->description) <= 50){
            return $this->description;
        } else {
            return substr($this->description, 0,50 ).'...';
        }
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    #[SerializedName("description")]
    #[Groups(["user:write"])]
    public function setTextDescription(?string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getNumberOfPages(): ?int
    {
        return $this->numberOfPages;
    }

    public function setNumberOfPages(int $numberOfPages): self
    {
        $this->numberOfPages = $numberOfPages;

        return $this;
    }

    public function getDateOfRelease(): ?\DateTimeInterface
    {
        return $this->dateOfRelease;
    }

    public function getDateOfReleaseAgo(): string
    {
        return Carbon::instance($this->getDateOfRelease())->diffForHumans();
    }


    public function getUpdateDateOfRelease(): ?\DateTimeInterface
    {
        return $this->updateDateOfRelease;
    }

    public function setUpdateDateOfRelease(?\DateTimeInterface $updateDateOfRelease): self
    {
        $this->updateDateOfRelease = $updateDateOfRelease;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
