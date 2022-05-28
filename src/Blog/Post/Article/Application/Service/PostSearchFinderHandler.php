<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\SearchPostCommand;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PostSearchFinderHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository,
    ) {
    }

    public function __invoke(SearchPostCommand $command): array
    {
        return $this->articleRepository->findSearch($command->getSearch());
    }
}
