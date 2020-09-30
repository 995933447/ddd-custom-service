<?php
namespace Framework\Http\Server;

use Framework\Http\Server\Request\FastcgiRequest;
use Framework\Http\Server\Request\SwooleRequest;
use Framework\Http\Server\Response\FastcgiResponse;
use Framework\Http\Server\Response\SwooleResponse;
use Framework\Singleton\SingletonTrait;
use Framework\AbstractInterface\Http\Server\IoContextInterface;
USE Framework\AbstractInterface\Http\Server\RequestInterface;
use Framework\AbstractInterface\Http\Server\ResponseInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

class IOContext implements IoContextInterface
{
    use SingletonTrait;

    const FASTCGI_MODE = 0;

    const SWOOLE_MODE = 1;

    protected $contextMode;

    protected $requestHandlers = [
        self::FASTCGI_MODE => FastcgiRequest::class,
        self::SWOOLE_MODE => SwooleRequest::class
    ];

    protected $responseHandlers = [
        self::FASTCGI_MODE => FastcgiResponse::class,
        self::SWOOLE_MODE => SwooleResponse::class
    ];

    protected $requestInstanceInjects = [];

    protected $responseInstanceInjects = [];

    protected static $requestInstance;

    protected static  $responseInstance;

    protected function init()
    {
        $this->resetToEmptyContext();
    }

    public function resetToEmptyContext()
    {
        $this->contextMode = $this->requestInstance = $this->responseInstance= null;
        $this->requestInstanceInjects = $this->responseInstanceInjects = [];
    }

    public function setSwooleMode(Request $swoole_raw_request, Response $swoole_raw_response): IoContextInterface
    {
        $this->contextMode = static::SWOOLE_MODE;
        $this->requestInstanceInjects = [$this, $swoole_raw_request];
        $this->responseInstanceInjects = [$this, $swoole_raw_response];

        return $this;
    }

    public function setFastcgiMode(): IoContextInterface
    {
        $this->contextMode = static::FASTCGI_MODE;
        $this->requestInstanceInjects = [$this];
        $this->responseInstanceInjects = [$this];

        return $this;
    }

    public function isSwooleMode(): bool
    {
        return $this->contextMode === static::SWOOLE_MODE;
    }

    public function isFastcgiMode(): bool
    {
        return $this->contextMode === static::FASTCGI_MODE;
    }

    public function getRequest(): RequestInterface
    {
        if (is_null(static::$requestInstance)) {
            return $this->makeRequest();
        }

        return static::$requestInstance;
    }

    public function reloadRequest(): RequestInterface
    {
        return $this->makeRequest();
    }

    protected function makeRequest(): RequestInterface
    {
        return static::$requestInstance = new $this->requestHandlers[$this->getContextMode()](...$this->requestInstanceInjects);
    }

    public function makeResponse(): ResponseInterface
    {
       return static::$responseInstance = new $this->responseHandlers[$this->getContextMode()](...$this->responseInstanceInjects);
    }

    public function getResponse(): ResponseInterface
    {
        if (is_null(static::$responseInstance)) {
            return $this->makeResponse();
        }

        return static::$responseInstance;
    }

    protected function getContextMode(): int
    {
        if (is_null($this->contextMode)) {
            throw new \RuntimeException("Please set http context mode.");
        }

        return $this->contextMode;
    }
}