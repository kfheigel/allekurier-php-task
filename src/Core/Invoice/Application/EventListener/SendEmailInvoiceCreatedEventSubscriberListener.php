<?php

declare(strict_types=1);

namespace App\Core\Invoice\Application\EventListener;

use App\Core\Invoice\Domain\Event\InvoiceCreatedEvent;
use App\Core\Invoice\Domain\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SendEmailInvoiceCreatedEventSubscriberListener implements EventSubscriberInterface
{
    public function __construct(private readonly NotificationInterface $mailer)
    {
    }

    public function send(InvoiceCreatedEvent $event): void
    {
        $this->mailer->sendEmail(
            $event->invoice->getUser()->getEmail(),
            'Invoice created',
            'Invoice has been created for your account'
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InvoiceCreatedEvent::class => 'send'
        ];
    }
}
