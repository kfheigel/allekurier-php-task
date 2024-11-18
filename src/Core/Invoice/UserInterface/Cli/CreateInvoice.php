<?php

declare(strict_types=1);

namespace App\Core\Invoice\UserInterface\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;

#[AsCommand(
    name: 'app:invoice:create',
    description: 'Adding new invoice'
)]
final class CreateInvoice extends Command
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (string) $input->getArgument('email');
        $amount = (int) $input->getArgument('amount');

        $createInvoiceCommand = new CreateInvoiceCommand($email, $amount);

        try {
            $this->bus->dispatch($createInvoiceCommand);
        } catch (ValidationFailedException $e) {
            $output->writeln('<error>Validation failed for CreateInvoiceCommand. Please provide correct data.</error>');

            foreach ($e->getViolations() as $violation) {
                $output->writeln('<error>' . $violation->getPropertyPath() . ': ' . $violation->getMessage() . '</error>');
            }

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
