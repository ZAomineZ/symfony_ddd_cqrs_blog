<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Domain\Event;

use App\Blog\Post\Category\Domain\Entity\Category;
use App\Shared\Event\DomainEventInterface;
use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

final class CategoryUpdatedEvent extends Event implements DomainEventInterface
{
    private DateTimeImmutable $dateEvent;

    public function __construct(private readonly ?Category $category)
    {
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDateEvent(): DateTimeImmutable
    {
        return $this->dateEvent;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }
}
