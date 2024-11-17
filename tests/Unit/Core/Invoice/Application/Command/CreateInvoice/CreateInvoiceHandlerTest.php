<?php

declare(strict_types=1);

namespace App\Tests\Unit\Core\Invoice\Application\Command\CreateInvoice;

use App\Tests\Common\UnitTestCase;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use App\Core\Invoice\Domain\Exception\InvoiceException;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceHandler;

class CreateInvoiceHandlerTest extends UnitTestCase
{
    private CreateInvoiceHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new CreateInvoiceHandler(
            $this->invoiceRepository,
            $this->userRepository
        );
    }

    public function test_handle_success(): void
    {
       // given
       $givenEmail = $this->faker->email();
       $user = $this->giveUser(
           email: $givenEmail,
           isUserActive: true
       );

       $command = new CreateInvoiceCommand($givenEmail, 12500);

       // when
       $this->handler->__invoke($command);

       // then
       $invoices = $this->invoiceRepository->getInvoicesWithGreaterAmountAndStatus(
        amount: 12000,
        invoiceStatus: InvoiceStatus::NEW
       );

       self::assertNotNull($invoices);
       self::assertCount(1, count($invoices);
       
       self::assertSame(12500, $invoices[0]->getAmount());
       self::assertSame($user, $invoices[0]->getUser());
    }

    public function test_handle_user_not_exists(): void
    {
        $this->expectException(UserNotFoundException::class);

        $command = new CreateInvoiceCommand('nonexistent@test.pl', 12500);

        $this->handler->__invoke($command);
    }

    public function test_handle_invoice_invalid_amount(): void
    {
        $this->expectException(InvoiceException::class);

        $givenEmail = $this->faker->email();
        $this->giveUser(
            email: $givenEmail,
            isUserActive: true
        );

        $command = new CreateInvoiceCommand($givenEmail, -5);

        $this->handler->__invoke($command);
    }
}
