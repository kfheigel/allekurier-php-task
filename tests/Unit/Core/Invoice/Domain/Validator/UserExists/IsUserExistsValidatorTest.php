<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Validator\UserExists;

use App\Tests\Common\ValidatorTestCase;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\Invoice\Domain\Validator\UserExists\IsUserExists;
use App\Core\Invoice\Domain\Validator\UserExists\IsUserExistsValidator;

final class IsUserExistsValidatorTest extends ValidatorTestCase
{
    private IsUserExists $givenConstraint;
    protected UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $this->assertInstanceOf(UserRepositoryInterface::class, $userRepository);
        $this->userRepository = $userRepository;

        $this->givenConstraint = new IsUserExists();
    }

    protected function createValidator(): IsUserExistsValidator
    {
        /** @var IsUserExistsValidator */
        return $this->container->get(IsUserExistsValidator::class);
    }

    /** @test */
    public function non_existing_user_raises_violation(): void
    {
        // given
        $givenNonExistingUser = "test@test.com";

        // when
        $this->validator->validate($givenNonExistingUser, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNonExistingUser)
            ->setCode($this->defaultViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function validator_sets_validation_code_given_in_constructor(): void
    {
        // given
        $givenNonExistingUser = "test@test.com";
        $givenViolationCode = 12345;

        // when
        $constraint = new IsUserExists(code: $givenViolationCode);
        $this->validator->validate($givenNonExistingUser, $constraint);

        // then
        $this->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $givenNonExistingUser)
            ->setCode((string) $givenViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function existing_user_passes_validation(): void
    {
        // given
        $givenUser = "test@test.com";
        $this->giveUser($givenUser);

        // when
        $this->validator->validate($givenUser, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }
}