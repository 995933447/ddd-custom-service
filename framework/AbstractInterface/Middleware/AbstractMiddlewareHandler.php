<?php
namespace Framework\AbstractInterface\Middleware;

use Closure;

/**
 * Abstract of Middleware.
 * Class AbstractMiddlewareHandler
 * @package Tanwan\classes\Middleware
 */
abstract class AbstractMiddlewareHandler
{
    final public function __construct()
    {
    }

    abstract public function handle(Closure $next, $data = null);
}