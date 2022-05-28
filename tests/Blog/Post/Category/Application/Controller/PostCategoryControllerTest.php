<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Category\Application\Controller;

use App\Blog\Post\Category\Domain\DataFixtures\CategoryFixture;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\HttpFoundation\Response;

final class PostCategoryControllerTest extends WebTestCaseWithDatabase
{
    private CategoryRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(CategoryFixture::class);

        $this->repository = PostCategoryControllerTest::getContainer()->get(CategoryRepositoryInterface::class);
    }

    public function testPostCategory()
    {
        $this->client->request("POST", '/categories/', [
            'name' => 'PHP',
            'slug' => 'php'
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('index');

        $this->assertNotNull($this->repository->find(11));
    }

    public function testPostCategoryBadCredentials()
    {
        $this->client->request("POST", '/categories/', [
            'fail' => 'PHP',
            'slug' => 'php'
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('categories');
    }
}
