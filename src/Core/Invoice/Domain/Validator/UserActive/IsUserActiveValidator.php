<?php

declare(strict_types=1);

namespace App\Core\Invoice\Domain\Validator\UserActive;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsUserActiveValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof isUserActive) {
            throw new UnexpectedTypeException($constraint, isUserActive::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var User $user */
        $user = $this->userRepository->findByEmail($value);

        if (null === $user) {
            return;
        }
        
        /** @var string $value */
        if (!$user->isUserActive()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
