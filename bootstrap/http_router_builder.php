<?php
use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;
use Framework\AbstractInterface\Http\Router\RouterInterface;
use Framework\Http\Router\Router;
use Framework\Http\Router\BaseRouteFinderNameEnum;

return function (
    IoContextInterface $io_context,
    AbstractMiddlewareRunner $router_middleware_runner,
    string $url_suffix = null,
    string $default_controller = null,
    string $default_action = null,
    string $root_controller_namespace = null
): RouterInterface {
    return Router::instance()
        ->setRouteFinder(
            BaseRouteFinderNameEnum::PATH_INFO_FINDER,
            [
                $io_context,
                $router_middleware_runner,
                $url_suffix,
                $default_controller,
                $default_action,
                $root_controller_namespace
            ]
        );
};