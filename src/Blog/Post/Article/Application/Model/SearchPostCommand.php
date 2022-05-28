<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Model;

final class SearchPostCommand
{
    public function __construct(
        private readonly ?string $search
    ) {
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }
}
