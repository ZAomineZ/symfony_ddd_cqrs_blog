<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Controller;

use App\Blog\Post\Category\Domain\Event\CategoryDeletedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/categories/{id}", name: "category_delete", methods: ['DELETE'])]
final class RemoveCategoryController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(int $id): RedirectResponse
    {
        $this->eventDispatcher->dispatch(new CategoryDeletedEvent($id));

        return $this->redirect('/', Response::HTTP_FOUND);
    }
}
