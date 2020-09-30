<?php
namespace Framework\ServeApplication\Swoole;
use Framework\AbstractInterface\Swoole\Websocket\Router\AbstractRouter;
use Framework\Exception\Swoole\Websocket\Router\NotFoundException;
use RuntimeException;
use Swoole\Websocket\Server as WebsocketServer;

class Application
{
    protected $config;

    protected $server;

    public function __construct(ApplicationConfigInterface $config)
    {
        $this->checkEnvironmentIfOk();

        $this->config = $config;
    }

    protected function checkEnvironmentIfOk()
    {
        if (!extension_loaded('swoole')) {
            throw new RuntimeException('Please install swoole extension.');
        }
    }

    protected function boot()
    {
        $this->setServeNetwork();
        $this->registerServiceItems();
    }

    protected function setServeNetwork()
    {
        if ($this->isEnableWebsocketServer()) {
            $this->server = $this->makeWebsocketServer();
        } else {
            throw new RuntimeException('Temporarily not supported for selected server type.');
        }

        $this->server->set($this->config->getServerConfig()->getServeOptions());

        foreach ($this->config->getServerConfig()->getServeLifecycleHooks() as $hook => $hook_handler) {
            if (!is_callable($hook_handler = new $hook_handler)) {
                throw new RuntimeException("Serve config hooks[$hook] is not callable.");
            }

            $this->server->on($hook, $hook_handler);
        }
    }

    protected function isEnableWebsocketServer(): bool
    {
        return $this->config->getServerConfig()->getServeType() === ServeTypeEnum::WEB_SOCKET_SERVER_TYPE;
    }

    protected function makeWebsocketServer(): WebsocketServer
    {
        return new WebsocketServer($this->config->getServerConfig()->getServeHost(), $this->config->getServerConfig()->getServePort());
    }

    protected function registerServiceItems()
    {
        if ($this->isEnableWebsocketServer()) {
            $this->registerWebsocketServiceItems();
        } else {
            throw new RuntimeException('Temporarily not supported for selected server type.');
        }
    }

    protected function registerWebsocketServiceItems()
    {
        $router = $this->makeRouter();

        $this->server->on('open', function($server, $request) use ($router) {
            try {
                $router->routeToHandleOnOpen(
                    $this->config->getServerConfig()->getServerName(),
                    $server,
                    $request
                );
            } catch (\Exception $exception) {
                $server->push($request->fd, $exception);
            }
        });

        $this->server->on('message', function($server, $frame) use ($router) {
            try {
                $router->routeToHandleOnMessage($this->config->getServerConfig()->getServerName(), $server, $frame);
            } catch (\Exception $exception) {
                $server->push($frame->fd, $exception);
            }
        });

        $this->server->on('close', function($server, $fd) use ($router) {
            try {
                $router->routeToHandleOnClose($this->config->getServerConfig()->getServerName(), $server, $fd);
            } catch (\Exception $exception) {
                $server->push($fd, $exception);
            }
        });
    }

    protected function makeRouter(): AbstractRouter
    {
        $router = $this->config->getRouterConfig()->getRouterHandler();

        if (is_string($router)) {
            $constructor = $this->config->getRouterConfig()->getConstructor();
            $router = new $router(...$constructor);
        }

        require $this->config->getRouterConfig()->getRouteFile();

        return $router;
    }

    protected function handle()
    {
        $this->server->start();
    }

    public function run()
    {
        $this->boot();
        $this->handle();
    }
}