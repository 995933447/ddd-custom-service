<?php
namespace Infrastructure\Persistence\Database\Redis;

/**
 * Redis connection config interface.
 * Class ConnectionConfig
 * @package Infrastructure\Persistence\Database\Redis
 */
class ConnectionConfig implements ConnectionConfigInterface
{
    private $host = '127.0.0.1';

    private $port = 6379;

    private $password = '';

    private $DBIndex = 1;

    private $timeout = 3.0;

    public function __construct(string $host = null, int $port = null, string $password = null, int $db_index = null, float $timeout = null)
    {
        if (!is_null($host)) {
            $this->host = $host;
        }

        if (!is_null($port)) {
            $this->port = $port;
        }

        if (!empty($password)) {
            $this->password = $password;
        }

        if (!is_null($db_index)) {
            $this->DBIndex = $db_index;
        }

        if (!is_null($timeout)) {
            $this->timeout = $timeout;
        }
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isSetPassword(): bool
    {
        return !is_null($this->password);
    }

    public function getDBIndex(): int
    {
        return $this->DBIndex;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }
}