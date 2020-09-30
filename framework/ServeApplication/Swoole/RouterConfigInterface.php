<?php
namespace Framework\ServeApplication\Swoole;

interface RouterConfigInterface
{
    public function getRouterHandler();

    public function getConstructor(): array;

    public function getRouteFile(): string;
}