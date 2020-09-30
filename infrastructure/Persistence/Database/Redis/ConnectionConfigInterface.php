<?php
namespace Infrastructure\Persistence\Database\Redis;

interface ConnectionConfigInterface
{
    public function getHost(): string;

    public function getPort(): int;

    public function getPassword(): string;

    public function isSetPassword(): bool;

    public function getDBIndex(): int;

    public function getTimeout(): float;
}