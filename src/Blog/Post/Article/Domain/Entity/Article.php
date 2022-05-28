<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Domain\Entity;

use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\User\Domain\Entity\User;
use App\Shared\Aggregate\AggregateRoot;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class Article extends AggregateRoot
{
    private string $id;

    #[
        Assert\NotBlank,
        Assert\Length(min: 5, max: 255)
    ]
    private ?string $title = null;

    #[
        Assert\NotBlank,
        Assert\Length(min: 5, max: 255)
    ]
    private ?string $slug = null;

    #[
        Assert\NotBlank,
        Assert\Length(min: 10)
    ]
    private ?string $body = null;

    private ?User $author = null;

    #[
        Assert\NotBlank
    ]
    private ?Category $category = null;

    private DateTimeImmutable $createdAt;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string|null $body
     */
    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function create(
        ?string $title = null,
        ?string $slug = null,
        ?string $body = null,
        ?User $user = null,
        ?Category $category = null
    ): self {
        $article = new Article();
        $article->setTitle($title);
        $article->setSlug($slug);
        $article->setBody($body);
        $article->setAuthor($user);
        $article->setCategory($category);
        $article->setCreatedAt(new DateTimeImmutable());

        return $article;
    }
}
