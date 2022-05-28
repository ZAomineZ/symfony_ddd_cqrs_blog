<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Service;

use App\Blog\Post\Category\Domain\Entity\Category;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateCategoryValidatorService implements MessageHandlerInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * @param Category $category
     * @return ConstraintViolation[]
     */
    public function __invoke(Category $category): array
    {
        $errorsData = [];

        foreach ($this->validator->validate($category) as $error) {
            $errorsData[] = $error;
        }

        $session = $this->requestStack->getSession();

        $session->set('errorsValidation', $this->validator->validate($category));

        return $errorsData;
    }
}
