<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    use EventsCollectorTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean", options={"default": true}, nullable=false)
     */
    private bool $isUserActive;

    public function __construct(string $email)
    {
        $this->id = null;
        $this->email = $email;
        $this->isUserActive = false;
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
