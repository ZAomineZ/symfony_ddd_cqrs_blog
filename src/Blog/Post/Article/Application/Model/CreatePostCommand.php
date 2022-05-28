<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Model;

use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\User\Domain\Entity\User;

final class CreatePostCommand
{
    public function __construct(
        private readonly ?string $title,
        private readonly ?string $slug,
        private readonly ?string $body,
        private readonly ?User $author,
        private readonly ?Category $category
    ) {
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }
}
