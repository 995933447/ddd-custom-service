<?php
namespace Domain\Model\User;

use Domain\AbstractEntity;

class User extends AbstractEntity
{
    protected $id;

    protected $username;

    protected $password;

    public function __construct(?int $id, string $username, HashedPassword $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public static function create(string $username, HashedPassword $password): User
    {
        return new static(null, $username, $password);
    }

    public function setId(int $id): void
    {
        if (!is_null($this->getId())) {
            throw new \RuntimeException("User id already exists.");
        }

        if ($id < 0) {
            throw new \InvalidArgumentException();
        }

        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function changeUsername(string $username): void
    {
        $this->username = $username;
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): HashedPassword
    {
        return $this->password;
    }

    public function checkPasswordIsSelf(HashedPassword $password): bool
    {
        return $this->getPassword()->equalsTo($password);
    }
}