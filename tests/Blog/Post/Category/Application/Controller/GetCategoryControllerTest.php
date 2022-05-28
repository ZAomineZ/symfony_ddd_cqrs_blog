<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Category\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetCategoryControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
    }

    public function testGetCategory()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/category/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertEquals('Category "Test #0"', $crawler->filter('h1')->text());
    }
}
