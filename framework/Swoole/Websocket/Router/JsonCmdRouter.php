<?php
namespace Framework\Swoole\Websocket\Router;

use Swoole\Websocket\Server;
use Swoole\Http\Request;
use Framework\AbstractInterface\Swoole\Websocket\Router\AbstractRouter;
use Framework\Exception\Swoole\Websocket\Router\NotFoundException;

class JsonCmdRouter extends AbstractRouter
{
    const ON_OPEN_HANDLE_ROUTE_KEY = 'on_open';

    const ON_CLOSE_HANDLE_ROUTE_KEY = 'on_close';

    const ON_MESSAGE_HANDLE_ROUTE_KEY = 'on_message';

    protected $routes;

    protected $foundRoutes;

    protected $controllerRootNameSpace;

    protected $routeKeyOfOnMessageData;

    protected $controllerActionSeparatorFromRouteData;

    public function __construct(
        string $controller_root_namespace,
        $route_key_of_on_message_data,
        string $controller_action_separator_from_route_data
    )
    {
        $this->controllerRootNameSpace = $controller_root_namespace;
        $this->routeKeyOfOnMessageData = $route_key_of_on_message_data;
        $this->controllerActionSeparatorFromRouteData = $controller_action_separator_from_route_data;
    }

    public function addPrefix(string $uri, \Closure $then)
    {
        if (!$this->hasSelectedServer()) {
            throw new \RuntimeException('Please set server name before setting prefix.');
        }

        $originalPrefix = $this->currentPrefix;

        $this->currentPrefix .= $uri;

        $then($this);

        $this->currentPrefix = $originalPrefix;
    }

    protected function getCurrentPrefix(): string
    {
        return !is_null($this->currentPrefix)? $this->currentPrefix: '/';
    }

    public function toAddOnOpenHandler(string $handler)
    {
        if (!$this->alreadySetOnOpenHandler($server = $this->getCurrentSelectedServer(), $prefix = $this->getCurrentPrefix())) {
            return $this->setOnOpenHandler($handler, $server, $prefix);
        }

        throw new \RuntimeException("On open handler already set on context[server:$server, prefix:$prefix]");
    }

    public function toAddOnCloseHandler(string $handler)
    {
        if (!$this->alreadySetOnCloseHandler($server = $this->getCurrentSelectedServer(), $prefix = $this->getCurrentPrefix())) {
            return $this->setOnCloseHandler($handler, $server, $prefix);
        }

        throw new \RuntimeException("On open handler already set on context[server:$server, prefix:$prefix]");
    }

    public function addOnMessageHandler(array $handler): AbstractRouter
    {
        if (!$this->alreadyExistOnMessageHandler($handler, $server = $this->getCurrentSelectedServer(), $prefix =$this->getCurrentPrefix())) {
            $this->pushOnMessageHandlers($handler, $server, $prefix);
            return $this;
        }

        throw new \RuntimeException("On message handler already exist {$handler} on context[server:$server, prefix:$prefix]");
    }

    protected function alreadySetOnOpenHandler(string $server, string $prefix = '/'): bool
    {
        return isset($this->routes[$server][$prefix][static::ON_OPEN_HANDLE_ROUTE_KEY]);
    }

    protected function setOnOpenHandler($handler, string $server, string $prefix = '/')
    {
        $this->routes[$server][$prefix][static::ON_OPEN_HANDLE_ROUTE_KEY] = $handler;
    }

    protected function alreadySetOnCloseHandler(string $server, string $prefix = '/'): bool
    {
        return isset($this->routes[$server][$prefix][static::ON_CLOSE_HANDLE_ROUTE_KEY]);
    }

    protected function setOnCloseHandler($handler, string $server, string $prefix = '/')
    {
        $this->routes[$server][$prefix][static::ON_CLOSE_HANDLE_ROUTE_KEY] = $handler;
    }

    protected function alreadyExistOnMessageHandler($handler, string $server, string $prefix = '/'): bool
    {
        if (!isset($this->routes[$server][$prefix][static::ON_MESSAGE_HANDLE_ROUTE_KEY])) {
            return false;
        }
        return in_array($handler, $this->routes[$server][$prefix][static::ON_MESSAGE_HANDLE_ROUTE_KEY]);
    }

    protected function pushOnMessageHandlers($handler, string $server, string $prefix = '/')
    {
        $this->routes[$server][$prefix][static::ON_MESSAGE_HANDLE_ROUTE_KEY] = $handler;
    }

    public function routeToHandleOnOpen(string $serverName, Server $server, Request $request)
    {
        if ($this->alreadySetOnOpenHandler($serverName, $uri = $request->server['request_uri']?: '/')) {
            $this->getOnOpenHandler($serverName, $uri)($server, $request);
        }

        if (is_null($route = $this->findRoute($serverName, $uri))) {
            throw new NotFoundException();
        }

        $this->saveFoundRoute($request->fd, $route);
    }

    protected function findRoute(string $server, string $prefix): ?array
    {
        return $this->routes[$server][$prefix]?? null;
    }

    protected function saveFoundRoute(int $fd, array $route)
    {
        $this->foundRoutes[$fd] = $route;
    }

    protected function getOnOpenHandler(string $server, string $uri): ?callable
    {
        return isset($this->routes[$server][$uri][static::ON_OPEN_HANDLE_ROUTE_KEY])?
            new $this->routes[$server][$uri][static::ON_OPEN_HANDLE_ROUTE_KEY]: null;
    }

    public function routeToHandleOnClose(string $serverName, Server $server, int $fd)
    {
        if (!is_null($handler = $this->getOnCloseHandlerOfFoundRoute($fd))) {
            $handler($server, $fd);
        }
    }

    protected function getOnCloseHandlerOfFoundRoute(int $fd): ?callable
    {
        return isset($this->foundRoutes[$fd][static::ON_CLOSE_HANDLE_ROUTE_KEY])?
            new $this->foundRoutes[$fd][static::ON_CLOSE_HANDLE_ROUTE_KEY]: null;
    }

    public function routeToHandleOnMessage(string $serverName, Server $server, $frame)
    {
        if (is_null($data = json_decode($frame->data, true)) || !isset($data[$this->routeKeyOfOnMessageData])) {
            throw new NotFoundException();
        }

        $command = trim($data[$this->routeKeyOfOnMessageData]);
        $split_position = strpos($command, $this->controllerActionSeparatorFromRouteData);

        $action = 'index';

        if ($split_position === false || $split_position <= 0) {
            $controller_name = $data['cmd'];
        } else {
            list($controller_name, $action) = explode($this->controllerActionSeparatorFromRouteData, $command);
        }

        $controller_name = $this->normalizeControllerName($controller_name);

        $on_message_handlers = $this->foundRoutes[$frame->fd][static::ON_MESSAGE_HANDLE_ROUTE_KEY]?? [];
        if (
            !in_array($controller_name, $on_message_handlers) &&
            !in_array(ltrim($controller_name, '\\'), $on_message_handlers)
        ) {
            throw new NotFoundException();
        }

        (new $controller_name)->$action($server, $frame, $data);
    }

    protected function normalizeControllerName(string $controller_name): string
    {
        return $this->controllerRootNameSpace . ucfirst($controller_name) . 'Controller';
    }
}