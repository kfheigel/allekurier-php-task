<?php

declare(strict_types=1);

namespace App\Core\Invoice\UserInterface\Cli;

use App\Common\Bus\QueryBusInterface;
use App\Core\Invoice\Application\DTO\InvoiceDTO;
use App\Core\Invoice\Application\Query\GetInvoicesByStatusAndAmountGreater\GetInvoicesByStatusAndAmountGreaterQuery;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:invoice:get-by-status-and-amount',
    description: 'Getting invoices id for a specific status and minimum amount'
)]
final class GetInvoices extends Command
{
    public function __construct(private readonly QueryBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $status = $input->getArgument('status');
        $amount = (int) $input->getArgument('amount');

        $invoiceStatus = InvoiceStatus::tryFrom($status);
        if ($invoiceStatus === null) {
            $output->writeln(sprintf("<error>Invalid invoice status '%s'. Allowed values are: %s.</error>", $status, $this->allowedStatuses()));
            return Command::FAILURE;
        }

        $invoices = $this->bus->dispatch(new GetInvoicesByStatusAndAmountGreaterQuery(
            $status,
            $amount
        ));

        $this->outputInvoicesInfo($status, $amount, $invoices, $output);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('status', InputArgument::REQUIRED, sprintf('The status of the invoices. Allowed values: %s', $this->allowedStatuses()));
        $this->addArgument('amount', InputArgument::REQUIRED, 'The minimum amount of the invoices (integer value)');
    }

    private function allowedStatuses(): string
    {
        return implode(', ', array_map(fn (InvoiceStatus $status) => $status->value, InvoiceStatus::cases()));
    }

    private function outputInvoicesInfo(
        string $status,
        int $amount,
        array $invoices,
        OutputInterface $output
    ): void {
        /** @var InvoiceDTO $invoice */
        $invoiceCount = 0;
        foreach ($invoices as $invoice) {
            $output->writeln((string) $invoice->id);
            $invoiceCount++;
        }

        if ($invoiceCount > 0) {
            $output->writeln(sprintf("<info>Found %d invoice(s) with status '%s' and amount greater than %d.</info>", $invoiceCount, $status, $amount));
        } else {
            $output->writeln(sprintf("<comment>No invoices found with status '%s' and amount greater than %d.</comment>", $status, $amount));
        }
    }
}
