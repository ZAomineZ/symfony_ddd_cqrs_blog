<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Article\Application\Controller;

use App\Blog\Post\Article\Domain\DataFixtures\PostFixture;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

final class GetAllPostsControllerTest extends WebTestCaseWithDatabase
{
    private Crawler $crawler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(PostFixture::class);

        $this->crawler = $this->client->request('GET', '/posts/');
    }

    public function testPosts()
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals('All Posts', $this->crawler->filter('h1')->text());
    }

    public function testPostsRender()
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(10, $this->crawler->filter('div.col-md-3')->count());
        $this->assertEquals("Article #0", $this->crawler->filter('div:first-child.col-md-3')->text());
    }

    public function testPostSearchWithAllString()
    {
        $dataQuery = ['q' => 'Article #1'];
        $crawler = $this->client
            ->request('GET', "/posts/?" . http_build_query($dataQuery));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(1, $crawler->filter('div.col-md-3')->count());
        $this->assertEquals("Article #1", $crawler->filter('div.col-md-3')->text());
    }

    public function testPostSearchBasic()
    {
        $dataQuery = ['q' => 'Article'];
        $crawler = $this->client
            ->request('GET', "/posts/?" . http_build_query($dataQuery));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(10, $crawler->filter('div.col-md-3')->count());
        $this->assertEquals("Article #0", $crawler->filter('div:first-child.col-md-3')->text());
    }

    public function testPostSearchBasicWithStrLower()
    {
        $dataQuery = ['q' => 'article'];
        $crawler = $this->client
            ->request('GET', "/posts/?" . http_build_query($dataQuery));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(10, $crawler->filter('div.col-md-3')->count());
        $this->assertEquals("Article #0", $crawler->filter('div:first-child.col-md-3')->text());
    }
}
