<?php

declare(strict_types=1);

namespace App\Tests\TestTemplates;

use App\Tests\Common\UnitTestCase;
use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

abstract class InvoiceRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): InvoiceRepositoryInterface;
    abstract protected function save(Invoice $invoice): void;

    /** @test */
    public function add_and_get_invoices_with_greater_than_amount_and_status(): void
    {
        // given
        $givenEmail = $this->faker->email();
        $givenUser = $this->giveUser($givenEmail);
        $givenAmount = $this->faker->randomNumber(2, false);
        $givenLessAmount = $givenAmount - 1;
        $givenInvoice = $this->giveInvoice($givenUser, $givenAmount);

        // when
        $invoice = $this->repository()->getInvoicesWithGreaterAmountAndStatus($givenLessAmount, InvoiceStatus::NEW);

        // then
        self::assertEquals($givenInvoice, $invoice[0]);
    }

    /** @test */
    public function dont_get_invoices_with_greater_than_amount_and_different_status(): void
    {
        // given
        $givenEmail = $this->faker->email();
        $givenUser = $this->giveUser($givenEmail);
        $givenAmount = $this->faker->randomNumber(2, false);
        $givenLessAmount = $givenAmount - 1;
        $this->giveInvoice($givenUser, $givenAmount);

        // when
        $invoice = $this->repository()->getInvoicesWithGreaterAmountAndStatus($givenLessAmount, InvoiceStatus::CANCELED);

        // then
        $this->assertEmpty($invoice);
    }

    /** @test */
    public function dont_get_invoices_with_the_same_amount_and_status(): void
    {
        // given
        $givenEmail = $this->faker->email();
        $givenUser = $this->giveUser($givenEmail);
        $givenAmount = $this->faker->randomNumber(2, false);
        $this->giveInvoice($givenUser, $givenAmount);

        // when
        $invoice = $this->repository()->getInvoicesWithGreaterAmountAndStatus($givenAmount, InvoiceStatus::NEW);

        // then
        $this->assertEmpty($invoice);
    }
}