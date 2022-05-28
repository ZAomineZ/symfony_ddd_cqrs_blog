<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Model;

final class CreateCategoryCommand
{
    public function __construct(
        private readonly ?string $name,
        private readonly ?string $slug
    ) {
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
