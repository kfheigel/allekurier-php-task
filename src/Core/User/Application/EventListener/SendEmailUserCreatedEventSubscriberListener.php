<?php

declare(strict_types=1);

namespace App\Core\User\Application\EventListener;

use App\Common\Mailer\MailerInterface;
use App\Core\User\Domain\Event\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendEmailUserCreatedEventSubscriberListener implements EventSubscriberInterface
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function send(UserCreatedEvent $event): void
    {
        $this->mailer->send(
            $event->user->getEmail(),
            'Account created',
            'An account has been registered in the system. Account activation takes up to 24h'
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'send'
        ];
    }
}
