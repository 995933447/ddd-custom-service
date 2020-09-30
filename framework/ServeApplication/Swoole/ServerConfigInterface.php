<?php
namespace Framework\ServeApplication\Swoole;

interface ServerConfigInterface
{
    public function getServeHost(): string;

    public function getServePort(): int;

    public function getServeType(): int;

    public function getServeOptions(string $name = null):array;

    public function getServerName(): string;

    public function getServeLifecycleHooks(): array;
}