<?php

declare(strict_types=1);

namespace App\Tests\TestTemplates;

use App\Core\User\Domain\User;
use App\Tests\Common\UnitTestCase;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

abstract class UserRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): UserRepositoryInterface;
    abstract protected function save(User $user): void;

    /** @test */
    public function add_and_get_one_by_email(): void
    {
        // given
        $givenEmail = $this->faker->email();
        $givenUser = $this->giveUser($givenEmail);

        // when
        $user = $this->repository()->getByEmail($givenUser->getEmail());
        $this->assertNotNull($user);

        // then
        self::assertEquals($givenUser, $user);
    }

    /** @test */
    public function dont_get_one_by_email(): void
    {
        // given
        $givenEmail = $this->faker->email();

        // expect
        $this->expectException(UserNotFoundException::class);

        // when
        $this->repository()->getByEmail($givenEmail);
    }

    /** @test */
    public function add_and_find_one_by_email(): void
    {
        // given
        $givenEmail = $this->faker->email();
        $givenUser = $this->giveUser($givenEmail);

        // when
        $user = $this->repository()->findByEmail($givenUser->getEmail());
        $this->assertNotNull($user);

        // then
        self::assertEquals($givenUser, $user);
    }

    /** @test */
    public function dont_find_one_by_email(): void
    {
        // given
        $givenEmail = $this->faker->email();

        // when
        $user = $this->repository()->findByEmail($givenEmail);

        // then
        self::assertNull($user);
    }

    /** @test */
    public function add_and_dont_find_with_active_status(): void
    {
        // given
        $givenEmail = $this->faker->email();
        $givenUser = $this->giveUser(
            email: $givenEmail,
            isUserActive: true
        );

        // when
        $users = $this->repository()->findAllWithInactiveStatus();

        // then
        $this->assertEmpty($users);
    }

    /** @test */
    public function add_and_find_all_with_inactive_status(): void
    {
        // given
        $givenFirstEmail = $this->faker->email();
        $this->giveUser(
            email: $givenFirstEmail,
            isUserActive: false
        );

        $givenSecondEmail = $this->faker->email();
        $this->giveUser(
            email: $givenSecondEmail,
            isUserActive: false
        );

        // when
        $users = $this->repository()->findAllWithInactiveStatus();

        // then
        $this->assertCount(2, $users);
    }
}