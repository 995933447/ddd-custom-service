<?php
namespace Infrastructure\Shared\Cache;

/**
 * Abstract template of cache repository can easy operate
 * Class EasyCacheRepositoryAbstract
 * @package Tanwan\classes\easy_cache
 */
abstract class AbstractCacheRepository
{
    /**
     * Fetch value from cache, return default value if not exist.
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        if (!$this->has($name)) {
            return $this->normalizeDefault($default);
        }

        $this->mustGet($name);
    }

    /**
     * Fetch value from cache. store default value into cache if not exist.
     * @param string $name
     * @param int|null $timeout
     * @param $default
     */
    public function remember(string $name, ?int $timeout, $default)
    {
        if (!$this->has($name)) {
            $default = $this->normalizeDefault($default);
            $this->put($name, $default, $timeout);
            return $default;
        } else {
            return $this->mustGet($name);
        }
    }

    /**
     * Get real default value.
     * @param $default
     * @return mixed
     */
    protected function normalizeDefault($default)
    {
        if ($default instanceof \Closure) {
            return $default();
        }
        return $default;
    }

    /** Real fetching.
     * Real fetching method.
     * @param string $name
     * @return mixed
     */
    abstract protected function mustGet(string $name);

    /**
     * Store a key into cache.
     * @param string $name
     * @param $value
     * @param int|null $timeout
     * @return mixed
     */
    abstract public function put(string $name, $value, ?int $timeout = null);

    /**
     * Exist key from cache.
     * @param string $name
     * @return bool
     */
    abstract public function has(string $name): bool;

    /**
     * Increment key from cache.
     * @param string $name
     * @param int $amount
     * @return mixed
     */
    abstract public function increment(string $name, int $amount = 1);

    /**
     * Decrement key from cache.
     * @param string $name
     * @param int $amount
     * @return mixed
     */
    abstract public function decrement(string $name, int $amount = 1);

    /**
     * Fetch key from cache then delete it.
     * @param string $name
     * @return mixed
     */
    abstract public function pull(string $name);

    /**
     * Delete key from cache.
     * @param string $name
     * @return mixed
     */
    abstract public function forget(string $name);

    /**
     * Clean all key from cache.
     * @return mixed
     */
    abstract public function flush();
}