<?php
namespace Framework\Http\Server\Response;

use Framework\AbstractInterface\Http\Server\ResponseInterface;

class FastcgiResponse extends AbstractResponse
{
    protected $isEnded = false;

    public function gzip(int $level = -1): ResponseInterface
    {
        throw new \RuntimeException("Fascgi server not support gzip response.");
    }

    public function cookie(string $name, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $http_only = false, $is_raw = false): ResponseInterface
    {
        if (!$is_raw) {
            $handler = 'setcookie';
        } else {
            $handler = 'setrawcookie';
        }
        $handler($name, $value, $expire, $path, $domain, $secure, $http_only);
    }

    public function chunk(string $content): ResponseInterface
    {
        throw new \RuntimeException("Fascgi server not support chunk response.");
    }

    protected function getHeaderLines(): array
    {
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

        $header_lines[] = "HTTP/1.1 $this->statusCode $this->reason";

        foreach ($this->headers as $header => $value) {
            $header_lines[] = "$header: $value";
        }

        return $header_lines;
    }

    public function send(): ResponseInterface
    {
        if ($this->isFinishSend()) {
            throw new \RuntimeException("Response was end.");
        }

        $this->sendHeaders()->sendContent();

        $this->setFinishSend();

        return $this;
    }

    protected function sendHeaders(): FastcgiResponse
    {
        if (!headers_sent()) {
            $header_lines = $this->getHeaderLines();

            foreach ($header_lines as $header_line) {
                header($header_line);
            }
        }

        return $this;
    }

    protected function sendContent()
    {
        if ($this->isFinishSend()) {
            return $this;
        }

        if (!is_null($this->content)) {
            echo $this->content;
        }

        $this->end();
    }

    protected function end()
    {
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            @ob_end_flush();
        }
    }

    public function redirect(string $url, $status_code = 302)
    {
        if ($this->isFinishSend()) {
            throw new \RuntimeException("Response was finished.");
        }

        $this->status($status_code);
        $this->header('Location', $url);

        $this->send();
    }

    public function isFinishSend(): bool
    {
        return $this->isEnded;
    }

    protected function setFinishSend()
    {
        $this->isEnded = true;
    }
}