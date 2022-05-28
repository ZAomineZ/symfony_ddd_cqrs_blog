<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Application\Model\FindAllPostQuery;
use App\Blog\Post\Article\Application\Model\SearchPostCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/posts/", name: "posts", methods: ['GET'])]
final class GetAllPostsController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request): Response
    {
        $query = $request->query;
        if (null !== $query->get('q')) {
            $posts = $this->handle(new SearchPostCommand($query->get('q')));
        } else {
            $posts = $this->handle(new FindAllPostQuery());
        }

        return $this->render('post/index.html.twig', compact('posts'));
    }
}