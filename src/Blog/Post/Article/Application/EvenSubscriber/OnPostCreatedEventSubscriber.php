<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\EvenSubscriber;

use App\Blog\Post\Article\Domain\Event\PostCreatedEvent;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OnPostCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    ) {
    }

    #[ArrayShape([PostCreatedEvent::class => "string"])] public static function getSubscribedEvents(): array
    {
        return [
            PostCreatedEvent::class => 'createPost'
        ];
    }

    public function createPost(PostCreatedEvent $event)
    {
        $this->articleRepository->save($event->getArticle());
    }
}
