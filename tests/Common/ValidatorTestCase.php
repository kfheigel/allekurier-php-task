<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Core\User\Domain\User;
use App\Tests\Fixtures\UserBuilder;
use App\Tests\Fixtures\InvoiceBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Tests\Doubles\Repository\UserInMemoryRepository;
use App\Tests\Doubles\Repository\InvoiceInMemoryRepository;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

abstract class ValidatorTestCase extends ConstraintValidatorTestCase
{
    protected ContainerInterface $container;
    protected InvoiceRepositoryInterface $invoiceRepository;
    protected UserRepositoryInterface $userRepository;
    protected string $defaultViolationCode = '400';

    protected function setUp(): void
    {
        $kernelTestCase = new class extends KernelTestCase{
            public static function getContainer(): ContainerInterface {
                return parent::getContainer();
            }
            public static function bootKernel(array $options = []): KernelInterface {
                return parent::bootKernel();
            }
        };

        $kernelTestCase::bootKernel();
        $this->container = $kernelTestCase::getContainer();
        $this->container->set(InvoiceRepositoryInterface::class, new InvoiceInMemoryRepository());
        $this->container->set(UserRepositoryInterface::class, new UserInMemoryRepository());

        parent::setUp();
    }

    protected function giveInvoice(User $user, int $amount): void
    {
        $invoice = InvoiceBuilder::any()
            ->withUser($user)
            ->withAmount($amount)
            ->build();

        $this->invoiceRepository->save($invoice);
    }

    protected function giveUser(string $email, bool $isUserActive = false): void
    {
        $user = UserBuilder::any()
            ->withEmail($email)
            ->build();

        $user->setUserActive($isUserActive);

        $this->userRepository->save($user);
    }
}
