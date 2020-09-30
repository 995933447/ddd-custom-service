<?php
namespace Framework\Exception\Http\Router;

use Throwable;

class NotFoundHttpException extends \Exception
{
    public function __construct($message = "Route not found.", Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}