<?php

declare(strict_types=1);

namespace App\Core\User\Userinterface\Cli;

use App\Core\User\Domain\User;
use App\Common\Bus\QueryBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Core\User\Application\Query\GetInactiveUsersEmailQuery;

#[AsCommand(
    name: 'app:user:get-by-inactive',
    description: 'Collect inactive users email'
)]
final class GetInactiveUsersEmail extends Command
{
    public function __construct(private readonly QueryBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $emails = $this->bus->dispatch(new GetInactiveUsersEmailQuery());

        /** @var User $user */
        foreach ($emails as $email) {
            $output->writeln($email);
        }
        return Command::SUCCESS;
    }
}
