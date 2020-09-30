<?php
namespace Infrastructure\Persistence\Database\Redis;

use Infrastructure\Persistence\Database\Redis;

/**
 * Redis service instance factory
 * Interface RedisFactoryInterface
 * @package Infrastructure\Persistence\Database\Redis
 */
interface RedisFactoryInterface
{
    /**
     * Make Redis service instance.
     * @return RedisInterface
     */
    public static function make(): RedisInterface;
}