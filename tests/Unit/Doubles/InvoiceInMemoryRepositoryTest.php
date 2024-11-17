<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Core\Invoice\Domain\Invoice;
use App\Tests\TestTemplates\InvoiceRepositoryTestTemplate;
use App\Tests\Doubles\Repository\InvoiceInMemoryRepository;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

final class InvoiceInMemoryRepositoryTest extends InvoiceRepositoryTestTemplate
{
    protected function setUp():void
    {
        parent::setUp();

        $this->invoiceRepository = new InvoiceInMemoryRepository();
    }

    protected function repository(): InvoiceRepositoryInterface
    {
        return $this->invoiceRepository;
    }

    protected function save(Invoice $invoice): void
    {
        $this->invoiceRepository->save($invoice);
    }
}
