<?php

declare(strict_types=1);

namespace App\Core\Invoice\Application\Command\CreateInvoice;

use App\Core\Invoice\Domain\Validator\UserExists\IsUserExists;
use App\Core\Invoice\Domain\Validator\UserActive\IsUserActive;

final readonly class CreateInvoiceCommand
{
    public function __construct(
        #[IsUserActive(code: 400)]
        #[IsUserExists(code: 404)]
        public readonly string $email,
        public readonly int $amount
    ) {
    }
}
