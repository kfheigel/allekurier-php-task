<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\UserActive;

use App\Tests\Common\ValidatorTestCase;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\Invoice\Domain\Validator\UserActive\IsUserActive;
use App\Core\Invoice\Domain\Validator\UserActive\IsUserActiveValidator;

final class IsUserActiveValidatorTest extends ValidatorTestCase
{
    private IsUserActive $givenConstraint;
    protected UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $this->assertInstanceOf(UserRepositoryInterface::class, $userRepository);
        $this->userRepository = $userRepository;

        $this->givenConstraint = new IsUserActive();
    }

    protected function createValidator(): IsUserActiveValidator
    {
        /** @var IsUserActiveValidator */
        return $this->container->get(IsUserActiveValidator::class);
    }

    /** @test */
    public function user_with_non_active_email_raises_violation(): void
    {
        // given
        $givenNonActiveUser = "test@test.com";
        $this->giveUser($givenNonActiveUser);

        // when
        $this->validator->validate($givenNonActiveUser, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNonActiveUser)
            ->setCode($this->defaultViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function validator_sets_validation_code_given_in_constructor(): void
    {
        // given
        $givenNonActiveUser = "test@test.com";
        $this->giveUser($givenNonActiveUser);
        $givenViolationCode = 12345;

        // when
        $constraint = new IsUserActive(code: $givenViolationCode);
        $this->validator->validate($givenNonActiveUser, $constraint);

        // then
        $this->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $givenNonActiveUser)
            ->setCode((string) $givenViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function user_with_active_email_passes_validation(): void
    {
        // given
        $givenUser = "test@test.com";
        $isActive = true;
        $this->giveUser($givenUser, $isActive);

        // when
        $this->validator->validate($givenUser, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }
}