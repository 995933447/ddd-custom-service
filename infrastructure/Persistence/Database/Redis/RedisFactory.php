<?php
namespace Infrastructure\Persistence\Database\Redis;

use Infrastructure\Shared\Config\Config;

class RedisFactory implements RedisFactoryInterface
{
    protected static $redisInstance;

    public static function make(): RedisInterface
    {
        return new Redis(Config::get('redis'), 'www');
    }

    public static function get(): RedisInterface
    {
        if (is_null(static::$redisInstance)) {
            static::$redisInstance = static::make();
        }
        return static::$redisInstance;
    }
}