<?php

declare(strict_types=1);

namespace App\Core\Invoice\Domain\Validator\UserExists;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsUserExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 400, mixed $options = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->violationCode = (string) $code;
    }

    public string $message = 'User email: "{{ string }}" does not exist';
}
