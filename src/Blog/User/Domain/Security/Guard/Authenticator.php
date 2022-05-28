<?php

declare(strict_types=1);

namespace App\Blog\User\Domain\Security\Guard;

use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Authenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public final const LOGIN_ROUTE = 'auth_login';

    public function __construct(
        protected readonly UserRepositoryInterface $userRepository,
        protected readonly UrlGeneratorInterface $urlGenerator,
        protected readonly ValidatorInterface $validator,
        protected readonly RequestStack $requestStack
    ) {
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function authenticate(Request $request): Passport
    {
        $password = (string)$request->request->get('password');
        $username = (string)$request->request->get('username');
        $csrfToken = $request->request->get('csrf_token');

        // Validate Credentials
        $errors = $this->validateCredentials([
            'username' => $username !== "" ? $username : null,
            'password' => $password !== "" ? $password : null
        ]);
        if (0 !== $errors->count()) {
            $errorsMessage = [];
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $errorsMessage[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new AuthenticatorValidatorException('Login failed.', $errorsMessage);
        }

        return new Passport(
            new UserBadge($username, fn(?string $userIdentifier) => $this->userRepository->findByUsername($userIdentifier)),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('login', $csrfToken),
                new RememberMeBadge()
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('index'));
    }

    protected function validateCredentials(array $credentials): ConstraintViolationListInterface
    {
        $dataConstraints = new Collection([
            'fields' => [
                'username' => [
                    new NotBlank([
                        'message' => 'Username cannot be empty.'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 180,
                        'minMessage' => 'Username is too short. It should have 3 characters or more.',
                        'maxMessage' => 'Username is too long. It should have 180 characters os less.',
                    ])
                ],
                'password' => [
                    new NotBlank([
                        'message' => 'Password cannot be empty.'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 180,
                        'minMessage' => 'Password is too short. It should have 5 characters or more.',
                        'maxMessage' => 'Password is too long. It should have 180 characters os less.'
                    ])
                ]
            ]
        ]);

        $errors = $this->validator->validate($credentials, $dataConstraints);

        $session = $this->requestStack->getSession();
        if (0 !== $errors->count()) {
            $session->set('login_errors', $errors);
        } else {
            $session->remove('login_errors');
        }

        return $errors;
    }
}
