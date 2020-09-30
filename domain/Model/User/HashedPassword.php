<?php
namespace Domain\Model\User;

use Domain\AbstractValueObject;

class HashedPassword extends AbstractValueObject
{
    protected $hashed;

    protected function __construct(string $password = null, string $hashed = null)
    {
        if (!empty($hashed)) {
            $this->hashed = $hashed;
        } else if (empty($password)) {
            throw new \InvalidArgumentException('Original password can not be empty when hashed be empty');
        } else {
            $this->hashed = $this->hash($password);
        }
    }

    public static function fromPassword(string $password): HashedPassword
    {
        return new static($password);
    }

    public static function fromHashed(string $hashed): HashedPassword
    {
        return new static(null, $hashed);
    }

    protected function hash(string $password): string
    {
        return md5($password);
    }

    public function equalsTo(self $hashed_password): bool
    {
        return $this->getHashed() === $hashed_password->getHashed();
    }

    public function getHashed(): string
    {
        return $this->hashed;
    }
}