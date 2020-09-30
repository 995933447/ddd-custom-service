<?php
namespace Framework\AbstractInterface\Middleware;

interface MiddlewareInterface
{
    public function getHandler(): AbstractMiddlewareHandler;

    public function getArgument();
}