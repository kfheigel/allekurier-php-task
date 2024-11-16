<?php

declare(strict_types=1);

namespace App\Core\User\Domain\Event;

use App\Core\User\Domain\User;
use App\Common\EventManager\EventInterface;

abstract class AbstractUserEvent implements EventInterface
{
    public function __construct(public User $user)
    {
    }
}
