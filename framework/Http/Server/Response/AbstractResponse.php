<?php
namespace Framework\Http\Server\Response;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Http\Server\ResponseInterface;

abstract class AbstractResponse implements ResponseInterface
{
    protected $statusCode;

    protected $reason;

    protected $headers = [];

    protected $charset;

    protected $content;

    protected $boundContext;

    public function __construct(IoContextInterface $context)
    {
        $this->boundContext = $context;
    }

    public function getBoundContext(): IoContextInterface
    {
        return $this->boundContext;
    }

    public function header(string $header, string $value, bool $ucwords = true): ResponseInterface
    {
        if ($ucwords) {
            $header = $this->normalizeHeaderName($header);
        }

        $this->headers[$header] = $value;

        return $this;
    }

    protected function normalizeHeaderName($header)
    {
        $header_extracts = explode('-', $header);
        foreach ($header_extracts as &$header_extract) {
            $header_extract = ucfirst($header_extract);
        }
        return implode('-', $header_extracts);
    }

    protected function getHeader(string $header, bool $ucwords = true): ?string
    {
        if ($ucwords) {
            $header = $this->normalizeHeaderName($header);
        }

        return $this->headers[$header]?? null;
    }

    public function withHeader(string $header, string $value, bool $ucwords = true): ResponseInterface
    {
        if ($ucwords) {
            $header = $this->normalizeHeaderName($header);
        }

        $this->headers[$header] = isset($this->headers[$header])? "{$this->headers[$header]};$value": $value;

        return $this;
    }

    public function status(?int $status_code = null, string $reason = ''): ResponseInterface
    {

        if (
            !is_null($status_code) &&
            (
                !is_numeric($status_code) ||
                ($status_code < ResponseStatusEnum::HTTP_CONTINUE_CODE || $status_code > ResponseStatusEnum::HTTP_CONTINUE_CODE)
            )
        ) {
            new \InvalidArgumentException("Invalid http code.");
        }

        if (is_null($status_code)) {
            $status_code = ResponseStatusEnum::HTTP_OK_CODE;
        }

        if (empty($reason)) {
            $reason = ResponseStatusEnum::getReason($status_code);
        }

        $this->statusCode = $status_code;
        $this->reason = $reason;

        return $this;
    }

    public function charset(string $charset): ResponseInterface
    {
        $this->charset = $charset;
    }

    public function content(?string $content): ResponseInterface
    {
        $this->content = $content;

        return $this;
    }

    public function json($content): ResponseInterface
    {
        if (!is_string($content)) {
            $content = json_encode($content);
        }

        return $this->header('Content-type', 'application/json')->content($content);
    }
}