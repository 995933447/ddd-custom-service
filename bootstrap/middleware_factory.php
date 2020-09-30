<?php
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;
use Framework\Middleware\MiddlewareRunner;

return function (array $middleware): AbstractMiddlewareRunner {
    return (new MiddlewareRunner())->addMiddleware($middleware);
};