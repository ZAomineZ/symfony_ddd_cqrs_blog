<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Domain\Event\PostDeletedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/posts/{id}", name: "post_delete", methods: ['DELETE'])]
final class RemovePostController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(int $id): RedirectResponse
    {
        $this->eventDispatcher->dispatch(new PostDeletedEvent($id));

        return $this->redirect('/', Response::HTTP_FOUND);
    }
}
