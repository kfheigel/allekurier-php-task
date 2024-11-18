<?php

declare(strict_types=1);

namespace App\Core\Invoice\Domain\Event;

final class InvoiceCreatedEvent extends AbstractInvoiceEvent
{
    public function getUserEmail(): string
    {
        // $this->invoice->getUser()->getEmail();
        return '';
    }
}
