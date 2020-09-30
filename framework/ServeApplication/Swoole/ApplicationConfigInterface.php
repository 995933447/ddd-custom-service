<?php
namespace Framework\ServeApplication\Swoole;

interface ApplicationConfigInterface
{
    public function getServerConfig(): ServerConfigInterface;

    public function getRouterConfig(): RouterConfigInterface;
}