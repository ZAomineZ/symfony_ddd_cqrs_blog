<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Domain\DataFixtures\PostFixture;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\HttpFoundation\Response;

final class GetPostControllerTest extends WebTestCaseWithDatabase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(PostFixture::class);
    }

    public function testGetArticle()
    {
        $crawler = $this->client->request('GET', '/post/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertEquals('Post "Article #0"', $crawler->filter('h1')->text());
    }
}
