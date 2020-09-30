<?php
namespace Infrastructure\Cache;

use Infrastructure\Persistence\Database\Redis\RedisFactoryInterface;
use Infrastructure\Shared\Cache\AbstractCacheRepository;
use Redis;

/**
 * Redis implement EasyCache.
 * Class RedisEasyCache
 * @package Tanwan\classes\easy_cache\drivers
 */
class RedisCacheRepository extends AbstractCacheRepository
{
    protected $redis;

    protected $redisConnection;

    protected $keyPrefix;

    protected $redisFactory;

    public function __construct(string $key_prefix, string $redis_connection, RedisFactoryInterface $redis_factory)
    {
        $this->keyPrefix = $key_prefix;
        $this->redisConnection = $redis_connection;
        $this->redisFactory = $redis_factory;
    }

    public function connect(): Redis
    {
        if (is_null($this->redis)) {
            $this->redis = $this->redisFactory->make();
        }
        return $this->redis->connect($this->redisConnection);
    }

    protected function normalizeName(string $name): string
    {
        return $this->keyPrefix . $name;
    }

    protected function packValue($value)
    {
        return serialize($value);
    }

    protected function unpackValue($packed_value)
    {
        if (!$packed_value) return $packed_value;
        return unserialize($packed_value);
    }

    protected function mustGet(string $name)
    {
        return $this->unpackValue($this->connect()->get($this->normalizeName($name)));
    }

    public function put(string $name, $value, ?int $timeout = null)
    {
        if (is_null($timeout)) {
            $this->connect()->set($this->normalizeName($name), $this->packValue($value));
        } else {
            $this->connect()->setex($this->normalizeName($name), $timeout, $this->packValue($value));
        }
    }

    public function has(string $name): bool
    {
        return $this->connect()->exists($this->normalizeName($name));
    }

    public function increment(string $name, int $amount = 1)
    {
        $this->connect()->incrBy($this->normalizeName($name), $amount);
    }

    public function decrement(string $name, int $amount = 1)
    {
        $this->connect()->decrBy($this->normalizeName($name), $amount);
    }

    public function pull(string $name)
    {
        $value = $this->unpackValue($this->mustGet($this->normalizeName($name)));
        $this->forget($this->normalizeName($name));
        return $value;
    }

    public function forget(string $name)
    {
        $this->connect()->del($this->normalizeName($name));
    }

    public function flush()
    {
        $this->connect()->flushDB();
    }
}