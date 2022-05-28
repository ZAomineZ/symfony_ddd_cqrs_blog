<?php

declare(strict_types=1);

namespace App\Tests\Blog\User\Application\Validator;

use App\Tests\WebTestCaseWithDatabase;
use Generator;
use Symfony\Component\HttpFoundation\Response;

final class RegisterRequestValidatorTest extends WebTestCaseWithDatabase
{
    /**
     * @dataProvider providerCredentials
     *
     * @param array $credentials
     * @param string $message
     * @return void
     */
    public function testProviderInvalidCredentialsRegister(array $credentials, string $message): void
    {
        $crawler = $this->client
            ->request('GET', '/register');

        $form = $crawler->filter('form')->form($credentials);

        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertRouteSame('register');

        $this->assertSelectorTextContains($crawler->filter('.feedback-error')->text(), $message);
    }

    public function providerCredentials(): Generator
    {
        // Username error validation
        yield [
            'credentials' => [
                'register[username]' => null,
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => 'root13',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Username cannot be empty.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'te',
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => 'root13',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Username is too short. It should have 3 characters or more.'
        ];

        yield [
            'credentials' => [
                'register[username]' => "On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L'avantage du Lorem Ipsum sur un texte générique comme 'Du texte. Du texte. Du texte.' est qu'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes).",
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => 'root13',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Username is too long. It should have 180 characters os less.'
        ];

        // Email error validation
        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => null,
                'register[password]' => 'root13',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Email cannot be empty.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => '@d.d',
                'register[password]' => 'root13',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Email is too short. It should have 5 characters or more.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => "On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L'avantage du Lorem Ipsum sur un texte générique comme 'Du texte. Du texte. Du texte.' est qu'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes).",
                'register[password]' => 'root13',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Email is too long. It should have 180 characters os less.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => 'test',
                'register[password]' => 'root13',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Email must be to type "email"'
        ];

        // Email password validation
        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => null,
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Password cannot be empty.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => 'test',
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Password is too short. It should have 5 characters or more.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => "On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L'avantage du Lorem Ipsum sur un texte générique comme 'Du texte. Du texte. Du texte.' est qu'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes).",
                'register[password_confirm]' => 'root13'
            ],
            'message' => 'Password is too long. It should have 180 characters os less.'
        ];

        // Email password confirm validation
        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => 'root13',
                'register[password_confirm]' => null
            ],
            'message' => 'Password confirm cannot be empty.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => 'root13',
                'register[password_confirm]' => 'test'
            ],
            'message' => 'Password confirm is too short. It should have 5 characters or more.'
        ];

        yield [
            'credentials' => [
                'register[username]' => 'Test',
                'register[email]' => 'johndoe@test.fr',
                'register[password]' => 'root13',
                'register[password_confirm]' => "On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L'avantage du Lorem Ipsum sur un texte générique comme 'Du texte. Du texte. Du texte.' est qu'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes).",
            ],
            'message' => 'Password confirm is too long. It should have 180 characters os less.'
        ];
    }
}
