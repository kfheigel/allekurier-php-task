<?php

declare(strict_types=1);

namespace App\Core\Invoice\Domain\Validator\UserExists;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsUserExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof isUserExists) {
            throw new UnexpectedTypeException($constraint, isUserExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var string $value */
        if (!$this->userRepository->findByEmail($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
