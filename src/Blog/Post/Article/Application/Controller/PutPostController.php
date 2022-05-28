<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Application\Model\UpdatePostCommand;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Event\PostUpdatedEvent;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/posts/{id}", name: "post_put", methods: ['PUT'])]
final class PutPostController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(int $id, Request $request): RedirectResponse
    {
        $parameters = $request->request;
        $updatePostCommand = new UpdatePostCommand(
            $id,
            $parameters->get('title'),
            $parameters->get('slug'),
            $parameters->get('body'),
            $this->categoryRepository->findOneBy(['id' => $parameters->get('category')])
        );

        /** @var Article $post */
        [$post, $violationData] = $this->handle($updatePostCommand);

        if (count($violationData) > 0) {
            return $this->redirect("/post/$id");
        }

        $this->eventDispatcher->dispatch(new PostUpdateDEvent($post));

        return $this->redirect('/', Response::HTTP_FOUND);
    }
}
