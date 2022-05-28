<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Controller;

use App\Blog\User\Domain\Security\Guard\AuthenticatorValidatorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: "/login", name: "auth_login", methods: ['GET', 'POST'])]
final class LoginController extends AbstractController
{
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $validationErrors = [];

        $errors = $authenticationUtils->getLastAuthenticationError();

        if ($errors instanceof AuthenticatorValidatorException) {
            $validationErrors = $errors->getMessageData();
        }

        return $this->render('auth/login.html.twig', [
            'validationErrors' => $validationErrors
        ]);
    }
}
