<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\FindPostQuery;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PostFinderHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    ) {
    }

    public function __invoke(FindPostQuery $query): ?Article
    {
        $articleID = $query->getPostID();

        return $this->articleRepository->findOneBy(['id' => $articleID]);
    }
}
