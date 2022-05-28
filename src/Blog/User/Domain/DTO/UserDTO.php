<?php

declare(strict_types=1);

namespace App\Blog\User\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class UserDTO
{
    #[
        Assert\NotBlank(message: "Username cannot be empty."),
        Assert\Length(
            min: 3,
            max: 180,
            minMessage: 'Username is too short. It should have 3 characters or more.',
            maxMessage: 'Username is too long. It should have 180 characters os less.'
        )
    ]
    protected ?string $username = null;

    #[
        Assert\NotBlank(message: "Email cannot be empty."),
        Assert\Length(
            min: 5,
            max: 180,
            minMessage: 'Email is too short. It should have 5 characters or more.',
            maxMessage: 'Email is too long. It should have 180 characters os less.'
        ),
        Assert\Email(message: 'Email must be to type "email"')
    ]
    protected ?string $email = null;

    #[
        Assert\NotBlank(message: "Password cannot be empty."),
        Assert\Length(
            min: 5,
            max: 180,
            minMessage: 'Password is too short. It should have 5 characters or more.',
            maxMessage: 'Password is too long. It should have 180 characters os less.'
        )
    ]
    protected ?string $password = null;

    #[
        Assert\NotBlank(message: "Password confirm cannot be empty."),
        Assert\Length(
            min: 5,
            max: 180,
            minMessage: 'Password confirm is too short. It should have 5 characters or more.',
            maxMessage: 'Password confirm is too long. It should have 180 characters os less.'
        )
    ]
    protected ?string $password_confirm = null;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getPasswordConfirm(): ?string
    {
        return $this->password_confirm;
    }

    /**
     * @param string|null $password_confirm
     */
    public function setPasswordConfirm(?string $password_confirm): void
    {
        $this->password_confirm = $password_confirm;
    }
}
