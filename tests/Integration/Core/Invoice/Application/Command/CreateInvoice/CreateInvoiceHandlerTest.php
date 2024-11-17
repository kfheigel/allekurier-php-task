<?php

declare(strict_types=1);

namespace App\Tests\Integration\Core\Invoice\Application\Command\CreateInvoice;

use App\Tests\Common\IntegrationTestCase;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use App\Core\Invoice\Application\Command\CreateInvoice\CreateInvoiceCommand;

class CreateInvoiceHandlerTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
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
       $this->commandBus->dispatch($command);

       // then
       $invoices = $this->invoiceRepository->getInvoicesWithGreaterAmountAndStatus(
        amount: 12000,
        invoiceStatus: InvoiceStatus::NEW
       );

       self::assertNotNull($invoices);
       self::assertCount(1, $invoices);
       
       self::assertSame(12500, $invoices[0]->getAmount());
       self::assertSame($user, $invoices[0]->getUser());
    }

    public function test_handle_user_not_exists(): void
    {
        // given        
        $command = new CreateInvoiceCommand('nonexistent@test.pl', 12500);

        // expect
        $this->expectException(ValidationFailedException::class);

        // when
        $this->commandBus->dispatch($command);
    }

    public function test_handle_user_not_active(): void
    {
        // given  
        $givenEmail = $this->faker->email();
        $this->giveUser(
            email: $givenEmail,
            isUserActive: false
        );      
        $command = new CreateInvoiceCommand($givenEmail, 12500);

        // expect
        $this->expectException(ValidationFailedException::class);

        // when
        $this->commandBus->dispatch($command);
    }
}
