<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\EvenSubscriber;

use App\Blog\Post\Article\Domain\Event\PostCreatedEvent;
use App\Blog\Post\Article\Domain\Event\PostUpdatedEvent;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OnPostUpdatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    ) {
    }

    #[ArrayShape([PostUpdatedEvent::class => "string"])] public static function getSubscribedEvents(): array
    {
        return [
            PostUpdatedEvent::class => 'updatePost'
        ];
    }

    public function updatePost(PostUpdatedEvent $event)
    {
        $this->articleRepository->update($event->getArticle());
    }
}
