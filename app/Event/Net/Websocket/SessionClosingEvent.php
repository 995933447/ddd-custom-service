<?php
namespace App\Event\Net\Websocket;

use App\Listener\Issue\ForceUserExitFromIssueReplyRooms;
use Framework\AbstractInterface\Event\AbstractEvent;
use Swoole\Websocket\Server;

class SessionClosingEvent extends AbstractEvent
{
    protected $fd;

    protected $server;

    public function __construct(int $fd, Server $server)
    {
        $this->fd = $fd;
        $this->server = $server;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    public function getFd(): int
    {
        return $this->fd;
    }

    public function getListeners(): array
    {
        return [
          ForceUserExitFromIssueReplyRooms::class
        ];
    }
}