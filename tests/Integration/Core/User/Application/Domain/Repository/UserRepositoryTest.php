<?php

declare(strict_types=1);

namespace App\Tests\Integration\Core\User\Application\Domain\Repository;

use App\Core\User\Domain\User;
use App\Tests\TestTemplates\UserRepositoryTestTemplate;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

final class UserRepositoryTest extends UserRepositoryTestTemplate
{    
    protected function setUp():void
    {
        parent::setUp();
        self::bootKernel();
    }

    protected function repository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    protected function save(User $user): void
    {
        $this->userRepository->save($user);
        $this->userRepository->flush();
    }
}