<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Domain\Event;

use App\Blog\Post\Article\Domain\Entity\Article;
use App\Shared\Event\DomainEventInterface;
use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

final class PostCreatedEvent extends Event implements DomainEventInterface
{
    private DateTimeImmutable $dateEvent;

    public function __construct(private readonly ?Article $article)
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
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }
}
