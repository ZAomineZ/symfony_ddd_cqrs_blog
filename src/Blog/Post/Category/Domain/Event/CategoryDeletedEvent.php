<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Domain\Event;

use App\Shared\Event\DomainEventInterface;
use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

final class CategoryDeletedEvent extends Event implements DomainEventInterface
{
    private DateTimeImmutable $dateEvent;

    public function __construct(private readonly ?int $categoryID)
    {
    }

    /**
     * @return int|null
     */
    public function getCategoryID(): ?int
    {
        return $this->categoryID;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDateEvent(): DateTimeImmutable
    {
        return $this->dateEvent;
    }
}
