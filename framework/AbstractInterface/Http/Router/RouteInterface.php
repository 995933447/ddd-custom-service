<?php
namespace Framework\AbstractInterface\Http\Router;

use Framework\AbstractInterface\Http\Server\ResponseInterface;

/**
 * Route of destination.
 * Interface RouteInterface
 * @package Framework\Http\RouterInterface
 */
interface RouteInterface
{
    /**
     * Route to destination.
     * @return ResponseInterface
     */
    public function runToProcess(): ResponseInterface;
}