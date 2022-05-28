<?php

declare(strict_types=1);

namespace App\Blog\User\Domain\Security\Guard;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

final class AuthenticatorValidatorException extends CustomUserMessageAuthenticationException
{
}
