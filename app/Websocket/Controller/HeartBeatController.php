<?php
namespace App\Websocket\Controller;

use Swoole\Websocket\Server;

class HeartBeatController
{
    public function ping(Server $server, $frame)
    {
        $server->push($frame->fd, 'PONG');
    }
}