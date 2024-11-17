<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Tests\Doubles\Repository\UserInMemoryRepository;
use App\Tests\Doubles\Repository\InvoiceInMemoryRepository;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

trait PrepareRepositoryInMemoryTrait
{
    private function substituteRepositoryInMemoryImplementation(): void
    {
        $this->container->set(InvoiceRepositoryInterface::class, new InvoiceInMemoryRepository());
        $this->container->set(UserRepositoryInterface::class, new UserInMemoryRepository());
    }
}
