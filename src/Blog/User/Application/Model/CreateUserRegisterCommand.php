<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Model;

use phpDocumentor\Reflection\Types\Iterable_;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class CreateUserRegisterCommand
{
    public function __construct(
        private readonly ?Request $request,
        private readonly ?FormInterface $form
    ) {
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * @return FormInterface|null
     */
    public function getForm(): ?FormInterface
    {
        return $this->form;
    }
}
