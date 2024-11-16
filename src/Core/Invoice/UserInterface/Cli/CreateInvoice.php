<?php

declare(strict_types=1);

namespace App\Core\Invoice\UserInterface\Cli;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;

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
        $createInvoiceCommand = new CreateInvoiceCommand(
            $input->getArgument('email'),
            (int) $input->getArgument('amount')
        );

        try {
            $this->bus->dispatch($createInvoiceCommand);
        } catch (Exception $e) {
            $output->writeln('<error>Invoice creation failed - Provided Email is inactive or User does not exists</error>');
            return Command::FAILURE;
        }

        $output->writeln('<info>Invoice has been successfully created.</info>');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The email address for the invoice');
        $this->addArgument('amount', InputArgument::REQUIRED, 'The amount for the invoice (integer value)');
    }
}
