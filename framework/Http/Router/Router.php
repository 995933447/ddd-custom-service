<?php
namespace Framework\Http\Router;

use Framework\AbstractInterface\Http\Server\ResponseInterface;
use Framework\Exception\Http\Router\NotFoundHttpException;
use Framework\Http\Router\FindRouteServiceProvider\FindRouteServiceProviderInterface;
use Framework\Http\Router\FindRouteServiceProvider\ParsePathInfoToFindRouteServiceProvider;
use Framework\AbstractInterface\Http\Server\RequestInterface;
use Framework\AbstractInterface\Http\Router\RouterInterface;
use Framework\AbstractInterface\Http\Router\RouteInterface;
use Framework\AbstractInterface\Http\Router\RouteFinderInterface;
use Framework\Singleton\SingletonTrait;

/**
 * Facade of route system.
 * Class RouterInterface
 * @package Framework\Http\RouterInterface
 */
class Router implements RouterInterface
{
    use SingletonTrait;

    protected $findRouteServiceProviderHandlers = [
        ParsePathInfoToFindRouteServiceProvider::class
    ];

    /**
     * Route finder name with handler map.
     * @var array
     */
    protected $routeFinderBindings = [];

    /**
     * Selected route finder.
     * @var
     */
    protected $routerFinder;

    /**
     * Called by __construct.
     */
    protected function toConstruct()
    {
        $this->addBaseFindRouteServices();
    }

    /**
     * Init base route finders.
     */
    protected function addBaseFindRouteServices()
    {
        foreach ($this->findRouteServiceProviderHandlers as $findRouteServiceProviderHandler) {
            $findRouteServiceProvider = new $findRouteServiceProviderHandler;

            if (!$findRouteServiceProvider instanceof FindRouteServiceProviderInterface) {
                throw new \RuntimeException("$findRouteServiceProviderHandler not instanceof " . FindRouteServiceProviderInterface::class);
            }

            $findRouteServiceProvider->handle($this);
        }
    }

    /**
     * Add route finder from custom.
     * @param string $finder_name
     * @param $route_finder
     */
    public function addRouteFinder(string $finder_name, $route_finder)
    {
        if (isset($this->routeFinderBindings[$finder_name])) {
            throw new \InvalidArgumentException("Route finder:$finder_name has exist.");
        }

        if (!$route_finder instanceof \Closure && !is_string($route_finder) && !$route_finder instanceof RouteFinderInterface) {
            throw new \InvalidArgumentException("Route finder must be instance of " . RouteFinderInterface::class);
        }

        $this->routeFinderBindings[$finder_name] = $route_finder;
    }

    /**
     * Set use which route finder.
     * @param string $finder_name
     * @param array $finder_options
     * @return $this
     */
    public function setRouteFinder(string $finder_name, array $finder_options): RouterInterface
    {
        if (isset($this->routeFinderBindings[$finder_name])) {
            $this->routerFinder = $this->normalizeRouteFinder($this->routeFinderBindings[$finder_name], $finder_options);
        } else {
            throw new \InvalidArgumentException("RouterInterface finder:$finder_name not exist.");
        }

        return $this;
    }

    /**
     * Parse bound route finder.
     * @param $route_finder
     * @param array $finder_options
     * @return RouteFinderInterface
     */
    protected function normalizeRouteFinder($route_finder, array $finder_options = []): RouteFinderInterface
    {
        if ($route_finder instanceof \Closure) {
            $route_finder = $route_finder(...$finder_options);
        } elseif (is_string($route_finder)) {
            $route_finder = new $route_finder(...$finder_options);
        }

        if (!$route_finder instanceof RouteFinderInterface) {
            throw new \RuntimeException("Route finder must be instance of " . RouteFinderInterface::class);
        }

        return $route_finder;
    }

    /**
     * Get already set route finder.
     * @return RouteFinderInterface
     */
    protected function getRouterFinder(): RouteFinderInterface
    {
        if (is_null($this->routerFinder)) {
            throw new \RuntimeException("Please set route finder.");
        }

        return $this->routerFinder;
    }

    /**
     * Found destination route.
     * @param RequestInterface $request
     * @return RouteInterface
     * @throws NotFoundHttpException
     */
    public function findRoute(RequestInterface $request): RouteInterface
    {
        if (is_null($route = $this->getRouterFinder()->findRoute($request))) {
            throw new NotFoundHttpException();
        }

        return $route;
    }

    /**
     * Let route run to process.
     * @param RouteInterface $route
     */
    public function runRoute(RouteInterface $route): ResponseInterface
    {
        return $route->runToProcess();
    }
}