<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Category\Application\Controller;

use App\Blog\Post\Category\Domain\DataFixtures\CategoryFixture;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\HttpFoundation\Response;

final class PutCategoryControllerTest extends WebTestCaseWithDatabase
{
    private CategoryRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(CategoryFixture::class);

        $this->repository = PutCategoryControllerTest::getContainer()->get(CategoryRepositoryInterface::class);
    }

    public function testPutCategory()
    {
        $this->client->request("PUT", '/categories/1', [
            'name' => 'PHP',
            'slug' => 'php'
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('index');

        /** @var Category|null $category */
        $category = $this->repository->find(1);
        $this->assertNotNull($category);
        $this->assertEquals('PHP', $category->getName());
        $this->assertEquals('php', $category->getSlug());
    }

    public function testPostCategoryBadCredentials()
    {
        $this->client->request("PUT", '/categories/1', [
            'fail' => 'PHP',
            'slug' => 'php'
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('category');
    }
}
