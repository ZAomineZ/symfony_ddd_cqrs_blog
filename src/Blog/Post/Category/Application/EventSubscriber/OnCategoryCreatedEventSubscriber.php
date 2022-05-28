<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\EventSubscriber;

use App\Blog\Post\Category\Domain\Event\CategoryUpdatedEvent;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OnCategoryCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    #[ArrayShape([CategoryUpdatedEvent::class => "string"])] public static function getSubscribedEvents(): array
    {
        return [
            CategoryUpdatedEvent::class => 'createCategory'
        ];
    }

    public function createCategory(CategoryUpdatedEvent $categoryCreatedEvent)
    {
        $this->categoryRepository->save($categoryCreatedEvent->getCategory());
    }
}
