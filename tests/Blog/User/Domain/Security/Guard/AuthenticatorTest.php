<?php

declare(strict_types=1);

namespace App\Tests\Blog\User\Domain\Security\Guard;

use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use App\Blog\User\Domain\Security\Guard\Authenticator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AuthenticatorTest extends TestCase
{
    private Authenticator $authenticator;

    private UserRepositoryInterface|MockObject $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(UserRepositoryInterface::class)
            ->getMock();
        $urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)
            ->getMock();
        $validator = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        $requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->authenticator = new Authenticator(
            $this->userRepository,
            $urlGenerator,
            $validator,
            $requestStack
        );
    }

    public function testPassportCorrectOnAuthentication(): void
    {
        $request = new Request([], ['username' => 'johndoe']);
        $request->setSession(new Session(new MockArraySessionStorage()));

        $user = new User();
        $this->userRepository
            ->expects($this->once())
            ->method('findByUsername')
            ->with('johndoe')
            ->willReturn($user);

        $passport = $this->authenticator->authenticate($request);
        $this->assertEquals($passport->getUser(), $user);
        $this->assertTrue($passport->hasBadge(CsrfTokenBadge::class));
        $this->assertTrue($passport->hasBadge(PasswordCredentials::class));
    }
}
