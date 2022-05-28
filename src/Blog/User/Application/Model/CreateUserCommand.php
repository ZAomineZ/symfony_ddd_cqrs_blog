<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Model;

use Symfony\Component\HttpFoundation\Request;

final class CreateUserCommand
{
    public function __construct(
        private readonly ?Request $request
    ) {
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }
}
