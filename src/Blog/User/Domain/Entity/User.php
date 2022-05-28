<?php

namespace App\Blog\User\Domain\Entity;

use App\Blog\User\Domain\Event\UserCreatedEvent;
use App\Shared\Aggregate\AggregateRoot;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends AggregateRoot implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $id;

    private string $username;

    private string $email;

    private array $roles = [];

    private string $password;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public static function registerUser(
        string $username,
        string $email,
        array $roles,
        string $password
    ): User {
        $user = new self();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($password);

        $user->recordDomainEvent(new UserCreatedEvent($username));

        return $user;
    }
}
