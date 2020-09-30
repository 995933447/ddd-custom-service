<?php
namespace App\Service;

class QueryApplicationServeResult
{
    protected $error;

    protected $value;

    public function __construct(?ApplicationServeException $exception, $value)
    {
        $this->error = $exception;
        $this->value = $value;
    }

    public static function make(?ApplicationServeException $exception, $value)
    {
        return new static($exception, $value);
    }

    public function hasError(): bool
    {
        return !is_null($this->error);
    }

    public function getError(): ApplicationServeException
    {
        return $this->error;
    }

    public function getValue()
    {
        return $this->value;
    }
}