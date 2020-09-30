<?php
namespace Framework\Singleton;

/**
 * Singleton trait.
 * Traits SingletonTrait
 * @package Framework\Singleton
 */
trait SingletonTrait
{
    protected static $instance;

    final protected function __construct()
    {
        $this->toConstruct();
    }

    protected function toConstruct()
    {

    }

    public static function instance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function __clone()
    {
        throw new \RuntimeException(get_class($this) . " is a singleton instance.");
    }
}