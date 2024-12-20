<?php

declare(strict_types=1);

namespace App\Common\Bus;

interface QueryBusInterface
{
    /**
     * Dispatches the given message.
     *
     * @param object $message
     * @param array  $stamps
     *
     * @return mixed
     */
    public function dispatch($message, array $stamps = []);
}
