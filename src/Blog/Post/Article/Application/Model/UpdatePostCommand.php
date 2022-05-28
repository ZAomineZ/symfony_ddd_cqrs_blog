<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Model;

use App\Blog\Post\Category\Domain\Entity\Category;

final class UpdatePostCommand
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $title,
        private readonly ?string $slug,
        private readonly ?string $body,
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
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
