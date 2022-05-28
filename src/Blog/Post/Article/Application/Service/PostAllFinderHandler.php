<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\FindAllPostQuery;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PostAllFinderHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function __invoke(FindAllPostQuery $query): array
    {
        return $this->articleRepository->findAll();
    }
}
