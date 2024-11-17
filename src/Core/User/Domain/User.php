<?php

declare(strict_types=1);

namespace App\Core\User\Domain;

use Doctrine\ORM\Mapping as ORM;
use App\Core\User\Domain\Event\UserCreatedEvent;
use App\Common\EventManager\EventsCollectorTrait;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    use EventsCollectorTrait;

    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true], nullable: false)]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 300, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(type: "boolean", options: ["default" => true], nullable: false)]
    private bool $isUserActive = false;

    public function __construct(string $email)
    {
        $this->id = null;
        $this->email = $email;
        $this->isUserActive = false;
        
        $this->record(new UserCreatedEvent($this));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isUserActive(): bool
    {
        return $this->isUserActive;
    }

    public function setUserActive(bool $isUserActive): void
    {
        $this->isUserActive = $isUserActive;
    }
}
