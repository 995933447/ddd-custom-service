<?php
return [
    'server' => [
        'name' => 'default',
        'type' => \Framework\ServeApplication\Swoole\ServeTypeEnum::WEB_SOCKET_SERVER_TYPE,
        'host' => '0.0.0.0',
        'port' => 9501,
        'options' => [

        ],
        'hooks' => [
            'WorkerStart' => \App\Command\SwooleServer\Hooks\OnWorkerStartHandler::class
        ]
    ],
    'router' => [
        'handler' => \Framework\Swoole\Websocket\Router\JsonCmdRouter::class,
        'constructor' => [
            '\\App\\Websocket\\Controller\\',
            'cmd',
            '.'
        ],
        'file' => __DIR__ . '/../../route/websocket.php'
    ]
];