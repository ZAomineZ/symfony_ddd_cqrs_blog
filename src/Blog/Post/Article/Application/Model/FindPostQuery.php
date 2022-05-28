<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Model;

final class FindPostQuery
{
    public function __construct(private readonly int $postID)
    {
    }

    /**
     * @return int
     */
    public function getPostID(): int
    {
        return $this->postID;
    }
}
