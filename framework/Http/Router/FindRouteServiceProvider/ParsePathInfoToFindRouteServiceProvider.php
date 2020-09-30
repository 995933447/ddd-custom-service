<?php
namespace Framework\Http\Router\FindRouteServiceProvider;

use Framework\AbstractInterface\Http\Router\RouteFinderInterface;
use Framework\AbstractInterface\Http\Router\RouterInterface;
use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;
use Framework\Http\Router\BaseRouteFinderNameEnum;
use Framework\Http\Router\RouteFinder\ParsePathInfoFinder;

class ParsePathInfoToFindRouteServiceProvider implements FindRouteServiceProviderInterface
{
    public function handle(RouterInterface $router)
    {
        $router->addRouteFinder(
            BaseRouteFinderNameEnum::PATH_INFO_FINDER,
            function (
                IoContextInterface $io_context,
                AbstractMiddlewareRunner $route_middleware_runner,
                string $url_suffix = null,
                string $default_controller = null,
                string $default_action = null,
                string $root_controller_namespace = null
            ): RouteFinderInterface
            {
                $path_info_finder = new ParsePathInfoFinder($io_context, $route_middleware_runner);

                if ($url_suffix)
                    $path_info_finder->setUrlFakeStaticSuffix($url_suffix);

                if ($default_controller)
                    $path_info_finder->setDefaultDestinationController($default_controller);

                if ($default_action)
                    $path_info_finder->setDefaultDestinationAction($default_action);

                if ($root_controller_namespace)
                    $path_info_finder->setRootControllerNamespace($root_controller_namespace);

                return $path_info_finder;
            }
        );
    }
}