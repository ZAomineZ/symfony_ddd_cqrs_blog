<?php

declare(strict_types=1);

namespace App\Tests\Blog\User\Application\Controller;

use App\Blog\User\Domain\DataFixtures\UserFixture;
use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\HttpFoundation\Response;

final class LoginControllerTest extends WebTestCaseWithDatabase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->addFixture(UserFixture::class);
    }

    public function testGETLogin()
    {
        $crawler = $this->client
            ->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertEquals('Login', $crawler->filter('h1')->text());
    }

    public function testPOSTSuccessLogin()
    {
        $crawler = $this->client
            ->request('GET', '/login');

        $form = $crawler->selectButton("Submit")->form();
        $form->setValues([
            'username' => 'Username #1',
            'password' => 'testtes'
        ]);
        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('index');
    }

    public function testPOSTFailureLogin()
    {
        $crawler = $this->client
            ->request('GET', '/login');

        $form = $crawler->selectButton("Submit")->form();
        $form->setValues([
            'username' => 'Username #1',
            'password' => 'addadaddadadadadad'
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect();

        $this->assertRouteSame('auth_login');
    }
}
