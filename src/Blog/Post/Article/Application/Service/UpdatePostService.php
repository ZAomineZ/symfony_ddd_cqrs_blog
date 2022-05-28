<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\CreatePostCommand;
use App\Blog\Post\Article\Application\Model\UpdatePostCommand;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdatePostService implements MessageHandlerInterface
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly ValidatorInterface $validator,
        private readonly RequestStack $requestStack
    ) {
    }

    public function __invoke(UpdatePostCommand $command): array
    {
        /** @var Article $post */
        $post = $this->articleRepository->findOneBy(['id' => $command->getId()]);
        $post->setTitle($command->getTitle());
        $post->setSlug($command->getSlug());
        $post->setBody($command->getBody());
        $post->setCategory($command->getCategory());


        $violations = $this->validatorEntity($post);

        return [$post, $violations];
    }

    protected function validatorEntity(Article $article): array
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
