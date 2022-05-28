<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Model;

final class FindCategoryQuery
{
    public function __construct(private readonly int $categoryID)
    {
    }

    /**
     * @return int
     */
    public function getCategoryID(): int
    {
        return $this->categoryID;
    }
}
