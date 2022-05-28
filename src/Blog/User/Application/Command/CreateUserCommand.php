<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Command;

use App\Blog\User\Application\Service\CreateUserRegisterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUserCommand extends Command
{
    protected static $defaultName = "app:create-user";

    public function __construct(private readonly CreateUserRegisterService $createUserService)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setHelp('This command allows you create to user...')
            ->addArgument('username', InputArgument::REQUIRED, 'User username')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('role', InputArgument::REQUIRED, 'User role');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            "User Creator",
            "============",
            ""
        ]);

        $output->writeln("You are about to create user");

        $user = $this->createUserService->handle(
            $input->getArgument('username'),
            $input->getArgument('email'),
            [$input->getArgument('role')],
            $input->getArgument('password')
        );

        $output->writeln([
            'User Created : ',
            ''
        ]);

        $output->write($user);

        return Command::SUCCESS;
    }
}
