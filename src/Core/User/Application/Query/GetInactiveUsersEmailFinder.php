<?php

declare(strict_types=1);

namespace App\Core\User\Application\Query;

use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

#[AsMessageHandler]
final class GetInactiveUsersEmailFinder
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(GetInactiveUsersEmailQuery $query): array
    {
        $users = $this->userRepository->findAllWithInactiveStatus();

        return array_map(function (User $user) {
            return $user->getEmail();
        }, $users);
    }
}
