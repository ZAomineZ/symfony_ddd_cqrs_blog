<?php

declare(strict_types=1);

namespace App\Blog\User\Domain\Event;

use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event implements DomainEventInterface
{
    public function __construct(private readonly string $username)
    {
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
