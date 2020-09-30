<?php
use Framework\AbstractInterface\Swoole\Websocket\Router\AbstractRouter;

if (!$router instanceof AbstractRouter) {
    throw new RuntimeException("router not instanceof " . AbstractRouter::class);
}

$health_check_controllers = [
    \App\Websocket\Controller\HeartBeatController::class
];

$router->addServer('default', function (AbstractRouter $router) use ($health_check_controllers) {
    $router->addPrefix('/test', function (AbstractRouter $router) use ($health_check_controllers) {
        $router->addOnOpenHandler(\App\Websocket\Handler\OnOpen\TestHandler::class)
            ->addOnMessageHandler(
                array_merge(
                    $health_check_controllers,
                    [
                        \App\Websocket\Controller\TestController::class
                    ]
                )
            );
    });

    $router->addPrefix('/issue', function (AbstractRouter $router) use ($health_check_controllers) {
        $router->addOnOpenHandler(\App\Websocket\Handler\OnOpen\SessionHandler::class)
            ->addOnCloseHandler(\App\Websocket\Handler\OnClose\SessionHandler::class)
            ->addOnMessageHandler(
                array_merge(
                    $health_check_controllers,
                    [
                        \App\Websocket\Controller\IssueController::class
                    ]
                )
            );
    });
});