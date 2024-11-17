<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

final class InvoiceInMemoryRepository implements InvoiceRepositoryInterface
{
    private array $entities = [];

    public function save(Invoice $invoice): void
    {
        $this->entities[$invoice->getId()] = $invoice;
    }

    /**
     * @return Invoice[]
     */
    public function getInvoicesWithGreaterAmountAndStatus(int $amount, InvoiceStatus $invoiceStatus): array
    {
        return array_values(array_filter(
            $this->entities,
            function (Invoice $invoice) use ($amount, $invoiceStatus) {
                return $invoice->getStatus() === $invoiceStatus && $invoice->getAmount() > $amount;
            }
        ));
    }

    public function flush(): void
    {
    }
}
