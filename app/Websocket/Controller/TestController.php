<?php
namespace App\Websocket\Controller;

use Swoole\Websocket\Server;

class TestController
{
    public function index(Server $server, $frame)
    {
        var_dump($frame->data);
        $server->push($frame->fd, $frame->data);
    }
}