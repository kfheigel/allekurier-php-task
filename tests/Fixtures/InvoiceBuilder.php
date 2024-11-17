<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Faker\Factory;
use App\Core\User\Domain\User;
use App\Core\Invoice\Domain\Invoice;

final class InvoiceBuilder
{
    private User $user;
    private int $amount;

    public function withUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function withAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public static function any(): self
    {
        return new InvoiceBuilder();
    }

    public function build(): Invoice
    {
        $faker = Factory::create();
        
        return new Invoice(
            $this->user ?? UserBuilder::any()->build(),
            $this->amount ?? $faker->randomNumber(1, false)
        );
    }
}
