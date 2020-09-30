<?php
namespace Domain\Model\User;

interface UserRepository
{
    public function findByUsername(string $username): ?User;

    public function findById(int $id): ?User;

    public function existsById(int $id): bool;
}