<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Domain\Event;

use App\Shared\Event\DomainEventInterface;
use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

final class PostDeletedEvent extends Event implements DomainEventInterface
{
    private DateTimeImmutable $dateEvent;

    public function __construct(private readonly ?int $articleID)
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
     * @return int|null
     */
    public function getArticleID(): ?int
    {
        return $this->articleID;
    }
}
