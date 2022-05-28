<?php

declare(strict_types=1);

namespace App\Tests\Blog\User\Application\Controller;

use App\Blog\User\Domain\DataFixtures\UserFixture;
use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

final class RegisterControllerTest extends WebTestCaseWithDatabase
{
    protected UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(UserFixture::class);

        $this->userRepository = RegisterControllerTest::getContainer()
            ->get(UserRepositoryInterface::class);
    }

    public function testGETRegister()
    {
        $crawler = $this->client
            ->request("GET", '/register');

        $form = $crawler->filter('form');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals('Register', $crawler->filter('h1')->text());
        $this->assertNotNull($crawler->filter('input[name="register[username]"]')->html());
        $this->assertNotNull($crawler->filter('input[name="register[email]"]')->html());
        $this->assertNotNull($crawler->filter('input[name="register[password]"]')->html());
        $this->assertNotNull($crawler->filter('input[name="register[password_confirm]"]')->html());
        $this->assertEquals(5, $crawler->filter('input')->count());
        $this->assertNotNull($form);

        // Submit register form
        $this->postRegister($form);
    }

    protected function postRegister(Crawler $crawler)
    {
        $form = $crawler->form([
            'register[username]' => 'Vincent',
            'register[email]' => 'vincentcapek@gmail.com',
            'register[password]' => 'root13',
            'register[password_confirm]' => 'root13'
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var User $user */
        $user = $this->userRepository->find(11);
        $this->assertNotNull($user);
        $this->assertEquals('Vincent', $user->getUsername());
        $this->assertEquals('vincentcapek@gmail.com', $user->getEmail());
    }
}
