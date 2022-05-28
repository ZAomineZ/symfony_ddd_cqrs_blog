<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\CreatePostCommand;
use App\Blog\Post\Article\Domain\Entity\Article;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreatePostService implements MessageHandlerInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    public function __invoke(CreatePostCommand $command): array
    {
        $post = Article::create(
            $command->getTitle(),
            $command->getSlug(),
            $command->getBody(),
            $command->getAuthor(),
            $command->getCategory()
        );

        $violations = $this->validator->validate($post);

        return [$post, $violations];
    }
}
