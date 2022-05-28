<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\EvenSubscriber;

use App\Blog\Post\Article\Domain\Event\PostDeletedEvent;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OnCategoryDeletedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    ) {
    }

    #[ArrayShape([PostDeletedEvent::class => "string"])] public static function getSubscribedEvents(): array
    {
        return [
          PostDeletedEvent::class => 'deletePost'
        ];
    }

    public function deletePost(PostDeletedEvent $event)
    {
        $this->articleRepository->delete($event->getArticleID());
    }
}
