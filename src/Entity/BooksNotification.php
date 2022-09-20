<?php

namespace App\Entity;

use App\Repository\BooksNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooksNotificationRepository::class)]
class BooksNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Books $books = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notificationText = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooks(): ?Books
    {
        return $this->books;
    }

    public function setBooks(?Books $books): self
    {
        $this->books = $books;

        return $this;
    }

    public function getNotificationText(): ?string
    {
        return $this->notificationText;
    }

    public function setNotificationText(?string $notificationText): self
    {
        $this->notificationText = $notificationText;

        return $this;
    }
}
