<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Faker\Factory;
use App\Core\User\Domain\User;

final class UserBuilder
{
    private string $email;

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public static function any(): self
    {
        return new UserBuilder();
    }

    public function build(): User
    {
        $faker = Factory::create();
        
        return new User(
            $this->email ?? $faker->email(),
        );
    }
}
