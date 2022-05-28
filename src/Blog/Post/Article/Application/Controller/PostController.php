<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Application\Model\CreatePostCommand;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Event\PostCreatedEvent;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\EventDispatcher\EventDispatcherInterface;

#[Route(path: "/posts", name: "post_post", methods: ['POST'])]
final class PostController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $parameters = $request->request;
        $createPostCommand = new CreatePostCommand(
            $parameters->get('title'),
            $parameters->get('slug'),
            $parameters->get('body'),
            $user,
            $this->categoryRepository->findOneBy(['id' => $parameters->get('category')])
        );

        /** @var Article $post */
        [$post, $violationData] = $this->handle($createPostCommand);

        if (count($violationData) > 0) {
            return $this->redirect("/posts/");
        }

        $this->eventDispatcher->dispatch(new PostCreatedEvent($post));

        return $this->redirect('/', Response::HTTP_FOUND);
    }
}
