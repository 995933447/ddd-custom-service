<?php
namespace Framework\Exception\Http\Router;

use Throwable;

class NotAllowMethodHttpException extends \RuntimeException
{
    public function __construct($message = "Request method not allow.", Throwable $previous = null)
    {
        parent::__construct($message, 405, $previous);
    }
}