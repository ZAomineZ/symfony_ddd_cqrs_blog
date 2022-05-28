<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Domain\Repository;

use App\Blog\Post\Article\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    public function findOneBy(array $criteria, array $orderBt = null);

    /**
     * @param string $q
     * @return Article[]
     */
    public function findSearch(string $q): array;

    public function save(Article $article): void;

    public function update(Article $article): void;

    public function delete(?int $articleID): void;
}