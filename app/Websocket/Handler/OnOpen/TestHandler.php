<?php
namespace App\Websocket\Handler\OnOpen;

use Swoole\WebSocket\Server;
use Swoole\Http\Request;

class TestHandler
{
    public function __invoke(Server $server, Request $request)
    {
        var_dump($request->server);

        if ($x_token = $request->server['HTTP_X_TOKEN']?? null) {
            session_id($x_token);
        }
    }
}