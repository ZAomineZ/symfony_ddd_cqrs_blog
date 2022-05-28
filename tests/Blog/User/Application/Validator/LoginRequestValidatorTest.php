<?php

declare(strict_types=1);

namespace App\Tests\Blog\User\Application\Validator;

use App\Tests\WebTestCaseWithDatabase;
use Generator;
use Symfony\Component\HttpFoundation\Response;

final class LoginRequestValidatorTest extends WebTestCaseWithDatabase
{
    /**
     * @dataProvider providerCredentials
     *
     * @param array $credentials
     * @param string $message
     * @return void
     */
    public function testProviderInvalidCredentialsLogin(array $credentials, string $message): void
    {
        $crawler = $this->client
            ->request('GET', '/login');

        $form = $crawler->filter('form')->form($credentials);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $this->client->followRedirect();

        $this->assertRouteSame('auth_login');

        $this->assertEquals($message, $crawler->filter('.feedback-error')->text());
    }

    public function providerCredentials(): Generator
    {
        yield [
            'credentials' => ['username' => null, 'password' => 'testdetest'],
            'message' => 'Username cannot be empty.'
        ];

        yield [
            'credentials' => ['username' => "da", 'password' => 'testdetest'],
            'message' => 'Username is too short. It should have 3 characters or more.'
        ];

        yield [
            'credentials' => [
                'username' => "On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L'avantage du Lorem Ipsum sur un texte générique comme 'Du texte. Du texte. Du texte.' est qu'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes).",
                'password' => 'testdetest'
            ],
            'message' => 'Username is too long. It should have 180 characters os less.'
        ];

        yield [
            'credentials' => ['username' => "Vincent", 'password' => ''],
            'message' => 'Password cannot be empty.'
        ];

        yield [
            'credentials' => ['username' => "Vincent", 'password' => 'dadd'],
            'message' => 'Password is too short. It should have 5 characters or more.'
        ];

        yield [
            'credentials' => [
                'username' => "Vincent",
                'password' => "On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L'avantage du Lorem Ipsum sur un texte générique comme 'Du texte. Du texte. Du texte.' est qu'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes)."
            ],
            'message' => 'Password is too long. It should have 180 characters os less.'
        ];
    }
}
