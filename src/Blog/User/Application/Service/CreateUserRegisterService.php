<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Service;

use App\Blog\User\Application\Model\CreateUserRegisterCommand;
use App\Blog\User\Domain\DTO\UserDTO;
use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateUserRegisterService implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(CreateUserRegisterCommand $command): ?User
    {
        $form = $command->getForm();
        $request = $command->getRequest();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserDTO $userDTO */
            $userDTO = $form->getData();
            $user = User::registerUser(
                $userDTO->getUsername(),
                $userDTO->getEmail(),
                ['ROLE_USER'],
                $userDTO->getPassword()
            );

            $this->userRepository->save($user);

            foreach ($user->pullDomainEvents() as $domainEvent) {
                $this->eventDispatcher->dispatch($domainEvent);
            }
        }

        return $user ?? null;
    }
}
