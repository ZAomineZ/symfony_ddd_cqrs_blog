<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Controller;

use App\Blog\Post\Category\Application\Model\FindCategoryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/category/{id}", name: "category", methods: ['GET'])]
final class GetCategoryController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(int $id): Response
    {
        $category = $this->handle(new FindCategoryQuery($id));

        return $this->render('category/show.html.twig', compact('category'));
    }
}
