<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Service;

use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function handle(string $username, string $email, array $roles, string $password): string
    {
        $user = User::registerUser($username, $email, $roles, $password);

        $this->userRepository->save($user);

        foreach ($user->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->serializer->serialize($user, 'json');
    }
}
