<?php
namespace Framework\ServeApplication\Swoole;

use InvalidArgumentException;

class ApplicationConfig implements ApplicationConfigInterface
{
    protected $serverConfig;

    protected $routerConfig;

    public function __construct(array $config_array)
    {
       $this->parse($config_array);
    }

    protected function parse(array $config_array)
    {
        if (!isset($config_array['server'])) {
            throw new InvalidArgumentException('Application config[server] not set.');
        }

        $this->serverConfig = new ServerConfig($config_array['server']);

        if (!isset($config_array['router'])) {
            throw new InvalidArgumentException('Application config[config] not set.');
        }

        $this->routerConfig = new RouterConfig($config_array['router']);
    }

    public function getServerConfig(): ServerConfigInterface
    {
        return $this->serverConfig;
    }

    public function getRouterConfig(): RouterConfigInterface
    {
        return $this->routerConfig;
    }
}