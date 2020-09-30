<?php
namespace Infrastructure\Persistence\Database\Redis;

use Infrastructure\Persistence\Database\Redis\RedisInterface;

/**
 * Redis service
 * Class Redis
 * @package Infrastructure\Persistence\Database\Redis
 */
class Redis implements RedisInterface
{
    protected $config;

    protected $defaultConnection;

    protected $connectors= [];

    public function __construct(array $config, string $default_connection)
    {
        $this->config = $config;
        $this->defaultConnection = $default_connection;
    }

    public function connect(string $connection = null): ?\Redis
    {
        if (is_null($connection)) {
            $connection = $this->getDefaultConnection();
        }

        $connector = $this->makeConnector($connection);

        return $connector;
    }

    protected function makeConnector(string $connection): \Redis
    {
        if (!is_null($connector = $this->getConnector($connection))) {
            return $connector;
        }

        $connector = new \Redis();

        $connection_config = $this->getConnectionConfig($connection);

        $connector->connect($connection_config->getHost(), $connection_config->getPort(), $connection_config->getTimeout());

        if ($connection_config->isSetPassword()) {
            $connector->auth($connection_config->getPassword());
        }

        $connector->select($connection_config->getDBIndex());

        $this->saveConnector($connection, $connector);

        return $connector;
    }

    protected function saveConnector(string $connection, \Redis $connector)
    {
        $this->connectors[$connection] = $connector;
    }

    public function getConnector(string $connection): ?\Redis
    {
        if (
            !isset($this->connectors[$connection])
            || !(($connector = $this->connectors[$connection]) instanceof \Redis)
            || !$connector->ping()
        ) {
            return null;
        }

        return $this->connectors[$connection];
    }

    protected function getDefaultConnection()
    {
        return $this->defaultConnection;
    }

    protected function getConnectionConfig(string $connection = null): ConnectionConfig
    {
        if (is_null($connection)) {
            $connection = $this->getDefaultConnection();
        }

        if (!isset($this->config[$connection])) {
            throw new \InvalidArgumentException("Redis config[$connection] not exist.");
        }

        $connection_config = $this->config[$connection];

        return new ConnectionConfig(
            $connection_config['redisIp']?? null,
            $connection_config['redisPort']?? null,
            $connection_config['redisPwd']?? null,
            $connection_config['dbIndex']?? null,
            $connection_config['timeout']
        );
    }
}