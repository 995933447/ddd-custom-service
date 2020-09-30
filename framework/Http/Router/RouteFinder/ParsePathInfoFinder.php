<?php
namespace Framework\Http\Router\RouteFinder;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;
use Framework\Http\Router\Route\ControllerDestinationRoute\ControllerDestinationRoute;
use Framework\AbstractInterface\Http\Router\RouteFinderInterface;
use Framework\AbstractInterface\Http\Router\RouteInterface;
use Framework\AbstractInterface\Http\Server\RequestInterface;

/**
 * Parse url path info to find route.
 * Class ParsePathInfoFinder
 * @package Framework\Http\RouterInterface\RouteFinder
 */
class ParsePathInfoFinder implements RouteFinderInterface
{
    protected $urlFakeStaticSuffix;

    protected $foundRoute;

    public function __construct(IoContextInterface $io_context, AbstractMiddlewareRunner $route_middleware_runner)
    {
        $this->foundRoute = new ControllerDestinationRoute($io_context, $route_middleware_runner);
    }

    /**
     * Set fake url static suffix.
     * @param string $suffix
     * @return $this
     */
    public function setUrlFakeStaticSuffix(string $suffix): self
    {
        $this->urlFakeStaticSuffix = $suffix;

        return $this;
    }

    /**
     * Use default destination controller if not found route.
     * @param string $default_destination_controller
     * @return $this
     */
    public function setDefaultDestinationController(string $default_destination_controller): self
    {
        $this->foundRoute->setDestinationController($default_destination_controller);

        return $this;
    }

    /**
     * Use default destination action if not found route .
     * @param string $default_destination_action
     * @return $this
     */
    public function setDefaultDestinationAction(string $default_destination_action): self
    {
        $this->foundRoute->setDestinationAction($default_destination_action);

        return $this;
    }

    /**
     * Set namespace of found destination controller.
     * @param string $namespace
     * @return $this
     */
    public function setRootControllerNamespace(string $namespace): self
    {
        $this->foundRoute->setControllerNamespace($namespace);

        return $this;
    }

    public function findRoute(RequestInterface $request): ?RouteInterface
    {
        $uri = $request->uri();

        if (!is_null($this->urlFakeStaticSuffix) && (($pos = strripos($uri, ".$this->urlFakeStaticSuffix")) !== false)) {
            $uri = substr($uri, 0, $pos);
        }

        $path_info_array = explode('/', ltrim($uri, '/'));

        $controller_name = $path_info_array[0] ? array_shift($path_info_array) : null;
        $action_name = $path_info_array[0] ? array_shift($path_info_array) : null;

        $found_route = clone $this->foundRoute;

        if (!is_null($controller_name) && !is_null($action_name)) {
            $found_route->setDestinationController(ucfirst($controller_name));
            $found_route->setDestinationAction($action_name);

            if (count($path_info_array) > 0) {
                $this->parsePathParametersIntoGet(implode('/', $path_info_array), $request);
            }
        }

        if (!$found_route->hasDestination()) {
            return null;
        }

        return $found_route;
    }

    /**
     * Bind path parameters into $_GET. Ban alter super global array unless must to do that.(尽可能不修改超级全局变量,除非必须这么做)
     * @param string $pathParamsSegmentUri
     * @param RequestInterface $request
     */
    protected function parsePathParametersIntoGet(string $pathParamsSegmentUri, RequestInterface &$request)
    {
        $path_info_array = explode('/', $pathParamsSegmentUri);
        $path_info_length = isset($path_info_array[0]) && $path_info_array[0] ? count($path_info_array) : 0;
        for ($i = 0; $i < $path_info_length; $i += 2) {
            !isset($_GET[$path_info_array[$i]]) && $_GET[$path_info_array[$i]] = $path_info_array[$i + 1];
        }

        $request = $request->getBoundContext()->reloadRequest();
    }
}