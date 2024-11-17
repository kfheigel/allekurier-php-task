<?php

declare(strict_types=1);

namespace App\Core\User\Infrastructure\Persistance;

use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ) { 
    }

    public function getByEmail(string $email): User
    {
        $user = $this->findByEmail($email);

        if (null === $user) {
            throw new UserNotFoundException('User not existing');
        }

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :user_email')
            ->setParameter('user_email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        return $user;
    }

    public function findAllWithInactiveStatus(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.isUserActive = :is_user_active')
            ->setParameter('is_user_active', false)
            ->getQuery()
            ->getResult();
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);

        $events = $user->pullEvents();
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
