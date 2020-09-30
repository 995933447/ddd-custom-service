<?php
namespace Framework\AbstractInterface\Http\Router;

use Framework\AbstractInterface\Http\Server\RequestInterface;

/**
 * Found route of destination.
 * Interface RouteFinderInterface
 * @package Framework\Http\RouterInterface
 */
interface RouteFinderInterface
{
    /**
     * Parse request info to found route.
     * @param RequestInterface $request
     * @return RouteInterface
     */
    public function findRoute(RequestInterface $request): ?RouteInterface;
}