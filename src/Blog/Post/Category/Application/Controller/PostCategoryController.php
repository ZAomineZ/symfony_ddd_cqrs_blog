<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Controller;

use App\Blog\Post\Category\Application\Model\CreateCategoryCommand;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Event\CategoryUpdatedEvent;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/categories/", name: "category_post", methods: ['POST'])]
final class PostCategoryController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $parameters = $request->request;
        $createCategoryCommand = new CreateCategoryCommand($parameters->get('name'), $parameters->get('slug'));

        /** @var Category|null $category */
        $category = $this->handle($createCategoryCommand);

        if (count($this->handle($category)) > 0) {
            return $this->redirect('/categories/', Response::HTTP_FOUND);
        }
        $categoryCreateEvent = new CategoryUpdatedEvent($category);
        $category->recordDomainEvent($categoryCreateEvent);

        foreach ($category->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->redirect('/', Response::HTTP_FOUND);
    }
}
