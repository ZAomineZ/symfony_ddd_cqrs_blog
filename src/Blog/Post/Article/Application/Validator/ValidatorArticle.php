<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Validator;

use App\Blog\Post\Article\Domain\Entity\Article;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorArticle
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly RequestStack $requestStack
    ) {
    }

    public function validatorEntity(Article $article): array
    {
        $errorsData = [];

        foreach ($this->validator->validate($article) as $error) {
            $errorsData[] = $error;
        }

        $session = $this->requestStack->getSession();

        $session->get('errorsValidation', $this->validator->validate($article));

        return $errorsData;
    }
}
