<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Domain\DataFixtures\PostFixture;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\HttpFoundation\Response;

final class RemoveArticleControllerTest extends WebTestCaseWithDatabase
{
    private ArticleRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(PostFixture::class);

        $this->repository = RemoveArticleControllerTest::getContainer()
            ->get(ArticleRepositoryInterface::class);
    }

    public function testDeletePost()
    {
        $this->client->request('DELETE', '/posts/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('index');

        /** @var Article $post */
        $post = $this->repository->find(1);
        $this->assertNull($post);
    }
}
