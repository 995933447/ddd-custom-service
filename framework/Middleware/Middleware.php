<?php
namespace Framework\Middleware;

use Framework\AbstractInterface\Middleware\AbstractMiddlewareHandler;
use Framework\AbstractInterface\Middleware\MiddlewareInterface;

class Middleware implements MiddlewareInterface
{
    protected $handler;

    protected $argument;

    public function __construct(string $handler, $argument)
    {
        $this->handler = $handler;
        $this->argument = $argument;
    }

    public function getHandler(): AbstractMiddlewareHandler
    {
        return new $this->handler;
    }

    public function getArgument()
    {
        return $this->argument;
    }
}