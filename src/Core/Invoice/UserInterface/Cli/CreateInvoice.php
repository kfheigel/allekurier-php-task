<?php

declare(strict_types=1);

namespace App\Core\Invoice\UserInterface\Cli;

use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:invoice:create',
    description: 'Adding new invoice'
)]
class CreateInvoice extends Command
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new CreateInvoiceCommand(
            $input->getArgument('email'),
            (int) $input->getArgument('amount')
        ));

        $output->writeln('<info>Invoice has been successfully created.</info>');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The email address for the invoice');
        $this->addArgument('amount', InputArgument::REQUIRED, 'The amount for the invoice (integer value)');
    }
}
