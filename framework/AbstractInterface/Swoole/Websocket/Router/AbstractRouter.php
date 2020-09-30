<?php
namespace Framework\AbstractInterface\Swoole\Websocket\Router;

use Swoole\Websocket\Server;
use Swoole\Http\Request;

abstract class AbstractRouter
{
    protected $serverNames = [];

    protected $currentSelectedServer;

    protected $currentPrefix;

    /**
     * Register server name of current group.
     * @param string $name
     * @param \Closure $then
     */
    public function addServer(string $name, \Closure $then)
    {
        if ($this->hasSelectedServer()) {
            throw new \RuntimeException('Not allow call server method in closure of other called server method.');
        }

        if (in_array($name, $this->serverNames)) {
            throw new \InvalidArgumentException("Server name:{$name} already exist.");
        }

        $this->serverNames[] = $this->currentSelectedServer = $name;

        $then($this);

        $this->currentSelectedServer = null;
    }

    /**
     * Check if set server name.
     * @return bool
     */
    protected function hasSelectedServer(): bool
    {
        return !is_null($this->getCurrentSelectedServer());
    }

    /**
     * Get current used server name.
     * @return string|null
     */
    protected function getCurrentSelectedServer(): ?string
    {
        return $this->currentSelectedServer;
    }

    /**
     * Add current prefix of current route group.
     * @param string $uri
     * @param \Closure $then
     */
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

    /**
     * Add on open handler of current route group.
     * @param string $handler
     * @return $this
     */
    public function addOnOpenHandler(string $handler): AbstractRouter
    {
        if (method_exists($handler, '__invoke')) {
            $this->toAddOnOpenHandler($handler);
            return $this;
        }
        throw new \InvalidArgumentException();
    }

    /**
     * To do add open handler.
     * @param string $handler
     * @return mixed
     */
    abstract protected function toAddOnOpenHandler(string $handler);

    /**
     * Add on close handler of current route group.
     * @param string $handler
     * @return $this
     */
    public function addOnCloseHandler(string $handler): AbstractRouter
    {
        if (method_exists($handler, '__invoke')) {
            $this->toAddOnCloseHandler($handler);
            return $this;
        }
        throw new \InvalidArgumentException();
    }

    /**
     * To do add close handler of current route group.
     * @param string $handler
     * @return mixed
     */
    abstract protected function toAddOnCloseHandler(string $handler);

    /**
     * Add on message handlers of current route group.
     * @param array $handler
     * @return AbstractRouter
     */
    abstract public function addOnMessageHandler(array $handler): AbstractRouter;

    /**
     * Match current frame message to use on open handler.
     * @param string $serverName
     * @param Server $server
     * @param Request $request
     * @return mixed
     */
    abstract public function routeToHandleOnOpen(string $serverName, Server $server, Request $request);

    /**
     * Match current frame message to use on close handler.
     * @param string $serverName
     * @param Server $server
     * @param int $fd
     * @return mixed
     */
    abstract public function routeToHandleOnClose(string $serverName, Server $server, int $fd);

    /**
     * Match current frame message to use on message handler.
     * @param string $serverName
     * @param Server $server
     * @param $frame
     * @return mixed
     */
    abstract public function routeToHandleOnMessage(string $serverName, Server $server, $frame);
}