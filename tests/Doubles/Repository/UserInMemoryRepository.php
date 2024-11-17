<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Core\User\Domain\User;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

final class UserInMemoryRepository implements UserRepositoryInterface
{
    private array $entities = [];

    public function save(User $user): void
    {
        $this->entities[$user->getId()] = $user;
    }

    /**
     * @return User[]
     */
    public function findAllWithInactiveStatus(): array
    {
        return array_filter(
            $this->entities,
            function (User $user) {
                return !$user->isUserActive();
            }
        );
    }

    public function getByEmail(string $email): User
    {
        $user = $this->findByEmail($email);
    
        if ($user !== null) {
            return $user;
        }
    
        throw new UserNotFoundException('User not existing');
    }

    

    public function findByEmail(string $email): ?User
    {
        foreach ($this->entities as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
    
        return null;
    }

    public function flush(): void
    {
    }
}