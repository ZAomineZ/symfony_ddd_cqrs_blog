<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Category\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetAllCategoriesControllerTest extends WebTestCase
{
    public function testCategories()
    {
        $client = self::createClient();
        $crawler = $client->request("GET", '/categories/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals('All Categories', $crawler->filter('h1')->text());
    }
}
