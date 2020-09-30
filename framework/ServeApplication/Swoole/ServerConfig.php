<?php
namespace Framework\ServeApplication\Swoole;

use InvalidArgumentException;

class ServerConfig implements ServerConfigInterface
{
    use ConfigArrayRequiredItemValidatorTrait;

    protected $serverName;

    protected $serveHost;

    protected $servePort;

    protected $serverType;

    protected $serveLifeCycleHooks = [];

    protected $allowServerTypes = [
        ServeTypeEnum::WEB_SOCKET_SERVER_TYPE
    ];

    protected $serveOptions = [];

    protected $configArrayRequiredItems = [
        'name', 'type', 'host', 'port'
    ];

    public function __construct(array $config_array)
    {
        $this->parse($config_array);
    }

    protected function parse(array $config_array)
    {
        $this->validateRequiredItemsFromConfigArray($config_array, 'server');

        if (!is_string($host = $config_array['host']) && empty($host)) {
            throw new InvalidArgumentException('Server config[host] must be numeric.');
        }
        $this->serveHost = $config_array['host'];

        if (!is_int($config_array['port'])) {
            throw new InvalidArgumentException('Server config[port] must be numeric.');
        }
        $this->servePort = $config_array['port'];

        if (!in_array($config_array['type'], $this->allowServerTypes)) {
            throw new InvalidArgumentException('Server config[type] not allowed.');
        }
        $this->serverType = $config_array['type'];

        if (isset($config_array['options'])) {
            if (!is_array($config_array['options'])) {
                throw new InvalidArgumentException('Server config[options] must be a array.');
            }
            $this->serveOptions = $config_array['options'];
        }

        $this->serverName = $config_array['name'];

        if (isset($config_array['hooks'])) {
            if (!is_array($config_array['hooks'])) {
                throw new InvalidArgumentException('Server config[hooks] must be a array.');
            }
            $this->serveLifeCycleHooks = $config_array['hooks'];
        }
    }

    public function getServeHost(): string
    {
        return  $this->serveHost;
    }

    public function getServePort(): int
    {
        return $this->servePort;
    }

    public function getServeType(): int
    {
        return $this->serverType;
    }

    public function getServeOptions(string $name = null):array
    {
        if (is_null($name)) {
            return $this->serveOptions;
        }

        return $this->serveOptions[$name];
    }

    public function getServerName(): string
    {
        return $this->serverName;
    }

    public function getServeLifecycleHooks(): array
    {
        return $this->serveLifeCycleHooks;
    }
}