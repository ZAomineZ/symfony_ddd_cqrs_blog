<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Controller;

use App\Blog\Post\Category\Application\Model\UpdateCategoryCommand;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Event\CategoryUpdatedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/categories/{id}", name: "category_put", methods: ['PUT'])]
final class PutCategoryController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(int $id, Request $request): RedirectResponse
    {
        $parameters = $request->request;
        $updateCategoryCommand = new UpdateCategoryCommand(
            $id,
            $parameters->get('name'),
            $parameters->get('slug')
        );

        /** @var Category|null $category */
        [$category, $violationsData] = $this->handle($updateCategoryCommand);

        if (count($violationsData) > 0) {
            return $this->redirect("/category/$id", Response::HTTP_FOUND);
        }

        $this->eventDispatcher->dispatch(new CategoryUpdatedEvent($category));

        return $this->redirect('/', Response::HTTP_FOUND);
    }
}
