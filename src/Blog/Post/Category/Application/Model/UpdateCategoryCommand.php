<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Model;

final class UpdateCategoryCommand
{
    public function __construct(
        private readonly ?int $id,
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
