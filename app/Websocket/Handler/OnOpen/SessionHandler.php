<?php
namespace App\Websocket\Handler\OnOpen;

use App\Error\ErrorCode;
use App\Websocket\IO\Push\JsonMessage;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;
use Swoole\Websocket\Server;
use Swoole\Http\Request;
use Infrastructure\Encrypting\JwtEncrypting;
use Infrastructure\Persistence\Database\Redis\RedisFactory;

class SessionHandler
{
    public function __invoke(Server $server, Request $request)
    {
        $redis = RedisFactory::get();

        $forget_user = function () use ($redis, $request) {
            if ($user_id = $redis->connect()->hGet(RedisKeyEnum::fdBoundUserIdHash(), $request->fd)) {
                $redis->connect()->hDel(RedisKeyEnum::fdBoundUserIdHash(), $request->fd);
                $redis->connect()->hDel(RedisKeyEnum::userIdBoundFdMapConnectTimeAtsHash($user_id), $request->fd);
            }
        };

        if (!isset($request->get['token']) || !$x_token = $request->get['token']) {
            $forget_user();

            JsonMessage::toPushErrorJson($server, $request->fd, '认证失败', ErrorCode::AUTH_ERROR_CORE);

            return $server->close($request->fd);
        }

        if (is_int($decoded = JwtEncrypting::decodeToken(null, $x_token))) {
            $forget_user();

            JsonMessage::toPushErrorJson($server, $request->fd, '认证失败', ErrorCode::AUTH_ERROR_CORE);

            return $server->close($request->fd);
        }

        $redis->connect()->hSet(RedisKeyEnum::userIdBoundFdMapConnectTimeAtsHash((int) $decoded['uid']), $request->fd, time());
        $redis->connect()->hSet(RedisKeyEnum::fdBoundUserIdHash(), $request->fd, $decoded['uid']);
    }
}