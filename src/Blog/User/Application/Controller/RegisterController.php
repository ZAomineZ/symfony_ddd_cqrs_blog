<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Controller;

use App\Blog\User\Application\Form\RegisterType;
use App\Blog\User\Application\Model\CreateUserRegisterCommand;
use App\Blog\User\Domain\DTO\UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/register", name: "register", methods: ['GET', 'POST'])]
final class RegisterController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request): Response
    {
        $user = new UserDTO();
        $form = $this->createForm(RegisterType::class, $user);

        $createUserCommand = new CreateUserRegisterCommand($request, $form);
        $user = $this->handle($createUserCommand);

        if (null !== $user) {
            return $this->redirectToRoute('auth_login');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
