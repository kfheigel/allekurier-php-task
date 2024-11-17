<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Core\User\Domain\User;
use App\Tests\TestTemplates\UserRepositoryTestTemplate;
use App\Tests\Doubles\Repository\UserInMemoryRepository;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

final class UserInMemoryRepositoryTest extends UserRepositoryTestTemplate
{
    protected function setUp():void
    {
        parent::setUp();

        $this->userRepository = new UserInMemoryRepository();
    }

    protected function repository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    protected function save(User $user): void
    {
        $this->userRepository->save($user);
    }
}
