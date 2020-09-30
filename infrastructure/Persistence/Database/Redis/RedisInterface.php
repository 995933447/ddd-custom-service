<?php
namespace Infrastructure\Persistence\Database\Redis;

interface RedisInterface
{
    public function __construct(array $config, string $default_connection);

    public function connect(string $connection = null): ?\Redis;
}