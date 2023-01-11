<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Title cannot be blank")]
    #[Assert\Length(max:30,maxMessage: "Title cannot be more than 30 char")]
    #[Assert\Regex('/^[a-zA-Z0-9.]+$/',"Title must be [a-zA-Z0-9.] pattern",null,true)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[Assert\NotBlank(message: "Author ID cannot be blank")]
    private ?Author $author = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Page cannot be blank")]
    #[Assert\Type(Types::INTEGER)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\LessThanOrEqual(1000)]
    private ?int $pages = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $release_date = null;

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

    public function getAuthorId(): ?Author
    {
        return $this->author;
    }

    public function setAuthorId(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(?int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(?\DateTimeInterface $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }
}
