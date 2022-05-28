<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Validator;

use App\Blog\Post\Article\Domain\Entity\Article;

interface ValidatorArticleInterface
{
    public function validatorEntity(Article $article): array;
}
