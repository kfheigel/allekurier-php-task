<?php

declare(strict_types=1);

namespace App\Tests\Common;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\Assert;
use App\Core\User\Domain\User;
use App\Tests\Fixtures\UserBuilder;
use App\Core\Invoice\Domain\Invoice;
use App\Tests\Fixtures\InvoiceBuilder;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;

abstract class UnitTestCase extends KernelTestCase
{
    use PrepareRepositoryInMemoryTrait;

    protected ContainerInterface $container;
    protected Generator $faker;
    protected UserRepositoryInterface $userRepository;
    protected InvoiceRepositoryInterface $invoiceRepository;
    protected MessageBusInterface $commandBus;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();

        $this->substituteRepositoryInMemoryImplementation();

        $userRepository = $this->container->get(UserRepositoryInterface::class);
        Assert::assertInstanceOf(UserRepositoryInterface::class, $userRepository);
        $this->userRepository = $userRepository;

        $invoiceRepository = $this->container->get(InvoiceRepositoryInterface::class);
        Assert::assertInstanceOf(InvoiceRepositoryInterface::class, $invoiceRepository);
        $this->invoiceRepository = $invoiceRepository;

        $commandBus = $this->container->get(MessageBusInterface::class);
        Assert::assertInstanceOf(MessageBusInterface::class, $commandBus);
        $this->commandBus = $commandBus;

        $this->faker = Factory::create();
    }

    protected function giveInvoice(User $user, int $amount): Invoice
    {
        $invoice = InvoiceBuilder::any()
            ->withUser($user)
            ->withAmount($amount)
            ->build();

        $this->invoiceRepository->save($invoice);

        return $invoice;
    }

    protected function giveUser(string $email, bool $isUserActive = false): User
    {
        $user = UserBuilder::any()
            ->withEmail($email)
            ->build();

        $user->setUserActive($isUserActive);

        $this->userRepository->save($user);

        return $user;
    }
}
