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

#[ORM\Entity(repositoryClass: BooksRepository::class)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'description' => 'partial'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
#[ApiResource(
    itemOperations: [
        "get"=>["path"=>"/getMyBook/{id}"],
        "put"
    ],
    shortName: "book",
    attributes: [
        "pagination_items_per_page" => 10
    ],
    denormalizationContext: ["groups" => ['write']],
    normalizationContext: ["groups" => ['read']]
)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read", "write"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["read"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["read", "write"])]
    private ?int $numberOfPages = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOfRelease = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updateDateOfRelease = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read", "write"])]
    private ?string $author = null;

    #[ORM\Column]
    #[Groups(["read", "write"])]
    private ?bool $isPublished = false;

    #[ORM\Column(nullable: true)]
    #[Groups(["read", "write"])]
    private ?int $price = null;

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

    #[Groups(["read", "write"])]
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

    #[Groups(["write"])]
    #[SerializedName("description")]
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

    #[Groups(["read"])]
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
}
