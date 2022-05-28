<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/categories/", name: 'categories', methods: 'GET')]
final class GetAllCategoriesController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(): Response
    {
        return $this->render('category/index.html.twig');
    }
}
