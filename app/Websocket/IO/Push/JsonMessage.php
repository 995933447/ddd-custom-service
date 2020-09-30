<?php
namespace App\Websocket\IO\Push;

use App\Error\ErrorCode;
use Swoole\Websocket\Server;

class JsonMessage
{
    public static function toPushNormalJson(Server $server, int $fd, array $data = [], int $code = ErrorCode::SUCCESS_CODE): void
    {
        $json = [
            'code' => $code,
        ];

        $data && $json['data'] = $data;

        $server->push($fd, json_encode($json));
    }

    public static function toPushErrorJson(Server $server, int $fd, string $message, int $code = ErrorCode::INTERNAL_ERROR_CODE): void
    {
        $json = [
            'code' => $code,
        ];

        $message && $json['message'] = $message;

        $server->push($fd, json_encode($json));
    }
}