<?php
namespace App\Websocket\Handler\OnClose;

use App\Event\Net\Websocket\SessionClosingEvent;
use Framework\Event\EventDispatcher;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;
use Swoole\Websocket\Server;
use Swoole\Http\Request;
use Infrastructure\Persistence\Database\Redis\RedisFactory;

class SessionHandler
{
    public function __invoke(Server $server, int $fd)
    {
        EventDispatcher::dispatch(new SessionClosingEvent($fd, $server));

        $redis = RedisFactory::get();

        if ($user_id = $redis->connect()->hGet(RedisKeyEnum::fdBoundUserIdHash(), $fd)) {
            $redis->connect()->hDel(RedisKeyEnum::fdBoundUserIdHash(), $fd);
            $redis->connect()->hDel(RedisKeyEnum::userIdBoundFdMapConnectTimeAtsHash($user_id), $fd);
        }
    }
}