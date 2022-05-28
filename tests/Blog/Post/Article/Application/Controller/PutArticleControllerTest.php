<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Domain\DataFixtures\PostFixture;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\HttpFoundation\Response;

final class PutArticleControllerTest extends WebTestCaseWithDatabase
{
    private ArticleRepositoryInterface $articleRepository;
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(PostFixture::class);

        $this->articleRepository = PostArticleControllerTest::getContainer()
            ->get(ArticleRepositoryInterface::class);
        $this->userRepository = PostArticleControllerTest::getContainer()
            ->get(UserRepositoryInterface::class);
    }

    public function testPostArticle()
    {
        /** @var Article $post */
        $post = $this->articleRepository->find(1);
        $this->login($post->getAuthor());

        $this->client->request('PUT', "/posts/1", [
            'title' => 'Amiraux vs Yonkos',
            'slug' => 'amiraux-vs-yonkos',
            'body' => 'Les yonkoss sont deux fois plus forts que nos amis les amiraux, c\'est factuel.',
            'category' => 1
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('index');

        /** @var Article|null $article */
        $article = $this->articleRepository->find(1);
        $this->assertNotNull($article);
        $this->assertEquals($post->getAuthor()->getUsername(), $article->getAuthor()->getUsername());
        $this->assertEquals('Amiraux vs Yonkos', $article->getTitle());
        $this->assertEquals('amiraux-vs-yonkos', $article->getSlug());
        $this->assertEquals('Les yonkoss sont deux fois plus forts que nos amis les amiraux, c\'est factuel.', $article->getBody());
        $this->assertEquals(1, $article->getCategory()->getId());
    }

    public function testPostArticleBadCredentials()
    {
        $this->login($this->userRepository->find(1));

        $this->client->request('PUT', '/posts/1', [
            'title' => 'f',
            'slug' => 'amiraux-vs-yonko',
            'body' => 'Les yonkos sont deux fois plus forts que nos amis les amiraux, c\'est factuel.',
            'category' => 1
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('post');
    }
}
