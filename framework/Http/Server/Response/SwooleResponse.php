<?php
namespace Framework\Http\Server\Response;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Http\Server\ResponseInterface;
use Swoole\Http\Response;

class SwooleResponse extends AbstractResponse
{
    protected $rawResponse;

    protected $isSending = false;

    protected $isEnd = false;

    public function __construct(IoContextInterface $context, Response $raw_response)
    {
        $this->rawResponse = $raw_response;

        parent::__construct($context);
    }

    public function send(): ResponseInterface
    {
        if ($this->isFinishSend()) {
            throw new \RuntimeException("Response was end.");
        }

        $this->setFinishSend();

        $this->sendHeaders()->sendContent();

        return $this;
    }

    protected function sendContent()
    {
        if (is_null($this->content)) {
            $this->rawResponse->end();
        } else {
            $this->rawResponse->end($this->content);
        }
    }

    protected function sendHeaders(): SwooleResponse
    {
        if ($this->isStartedSend()) {
            return $this;
        }

        if (!is_null($this->charset)) {
            if (is_null($this->getHeader('Content-Type'))) {
                $this->header('Content-type', "text/html; charset={$this->charset}");
            } else {
                $this->withHeader('Content-type', $this->charset);
            }
        }

        if (is_null($this->reason) || is_null($this->statusCode)) {
            $this->status();
        }

        $this->rawResponse->status($this->statusCode);

        foreach ($this->headers as $header => $value) {
            $this->rawResponse->header($header, $value);
        }

        $this->setStartedSend();

        return $this;
    }

    public function redirect(string $url, $status_code = 302)
    {
        $this->sendHeaders();

        $this->rawResponse->redirect($url, $status_code);
    }

    public function gzip(int $level = -1): ResponseInterface
    {
        $this->rawResponse->gzip($level);
    }

    public function cookie(string $name, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $http_only = false, $is_raw = false): ResponseInterface
    {
        if (!$is_raw) {
            $handler = 'setcookie';
        } else {
            $handler = 'setrawcookie';
        }
        $this->rawResponse->$handler($name, $value, $expire, $path, $domain, $secure, $http_only);
    }

    public function chunk(string $content): ResponseInterface
    {
        if ($this->isFinishSend()) {
            throw new \RuntimeException("Response was end.");
        }

        $this->sendHeaders();

        $this->rawResponse->write($content);
    }

    public function isFinishSend(): bool
    {
        return $this->isEnd;
    }

    protected function setStartedSend()
    {
        $this->isSending = true;
    }

    protected function setFinishSend()
    {
        $this->isEnd = true;
    }

    protected function isStartedSend()
    {
        return $this->isSending;
    }
}