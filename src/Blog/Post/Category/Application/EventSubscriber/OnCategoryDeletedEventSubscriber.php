<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\EventSubscriber;

use App\Blog\Post\Category\Domain\Event\CategoryDeletedEvent;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OnCategoryDeletedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    #[ArrayShape([CategoryDeletedEvent::class => "string"])] public static function getSubscribedEvents(): array
    {
        return [
            CategoryDeletedEvent::class => 'createCategory'
        ];
    }

    public function createCategory(CategoryDeletedEvent $categoryCreatedEvent)
    {
        $this->categoryRepository->delete($categoryCreatedEvent->getCategoryID());
    }
}
