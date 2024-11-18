<?php

declare(strict_types=1);

namespace App\Core\User\Userinterface\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Core\User\Application\Command\CreateUser\CreateUserCommand;

#[AsCommand(
    name: 'app:user:create',
    description: 'Adding new user'
)]
final class CreateUser extends Command
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new CreateUserCommand(
            $input->getArgument('email')
        ));

        $output->writeln('<info>User has been successfully created.</info>');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The email address of the user to be created');
    }
}
