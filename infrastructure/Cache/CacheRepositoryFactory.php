<?php
namespace Infrastructure\Cache;

use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Shared\Cache\AbstractCacheRepository;
use Infrastructure\Shared\Config\Config;

class CacheRepositoryFactory
{
    const REDIS_DRIVER = 'redis';

    public static function make(string $driver = null): AbstractCacheRepository
    {
        if (is_null($driver)) {
            $driver = Config::get('default');
        }

        switch ($driver) {
            case static::REDIS_DRIVER:
                return static::makeRedisCacheRepository();
        }
    }

    protected static function makeRedisCacheRepository(): RedisCacheRepository
    {
        $driver = Config::get("cache.drivers.redis");
        if (!isset($driver['prefix']) || !isset($driver['connection'])) {
            throw new \RuntimeException("Cache driver[redis] needs prefix option and connection option.");
        }
        return new RedisCacheRepository($driver['prefix'], $driver['connection'], new RedisFactory());
    }
}