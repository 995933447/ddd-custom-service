<?php
namespace Framework\Exception\Swoole\Websocket\Router;

use Throwable;

class NotFoundException extends \RuntimeException
{
    public function __construct($message = "Route not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}