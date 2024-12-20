<?php

declare(strict_types=1);

namespace App\Core\Invoice\Application\Query\GetInvoicesByStatusAndAmountGreater;

use App\Core\Invoice\Application\DTO\InvoiceDTO;
use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetInvoicesByStatusAndAmountGreaterFinder
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository
    ) {
    }

    public function __invoke(GetInvoicesByStatusAndAmountGreaterQuery $query): array
    {
        $invoices = $this->invoiceRepository->getInvoicesWithGreaterAmountAndStatus(
            $query->amount,
            InvoiceStatus::from($query->status)
        );

        return array_map(function (Invoice $invoice) {
            return new InvoiceDTO(
                $invoice->getId(),
                $invoice->getUser()->getEmail(),
                $invoice->getAmount()
            );
        }, $invoices);
    }
}
