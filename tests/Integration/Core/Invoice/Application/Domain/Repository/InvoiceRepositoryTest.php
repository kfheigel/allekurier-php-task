<?php

declare(strict_types=1);

namespace App\Tests\Integration\Core\User\Application\Domain\Repository;

use App\Core\Invoice\Domain\Invoice;
use App\Tests\TestTemplates\InvoiceRepositoryTestTemplate;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

final class InvoiceRepositoryTest extends InvoiceRepositoryTestTemplate
{
    protected function setUp():void
    {
        parent::setUp();
        self::bootKernel();
    }

    protected function repository(): InvoiceRepositoryInterface
    {
        return $this->invoiceRepository;
    }

    protected function save(Invoice $invoice): void
    {
        $this->invoiceRepository->save($invoice);
        $this->invoiceRepository->flush();
    }
}