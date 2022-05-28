<?php

declare(strict_types=1);

namespace App\Tests\Blog\Post\Article\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class HomeControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = self::createClient();
        $crawler = $client->request("GET", '/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals('Home Page', $crawler->filter('h1')->text());
    }
}
