<?php
namespace Framework\AbstractInterface\Http\Server;

use Framework\AbstractInterface\Http\Router\RouterInterface;

interface ResponseInterface
{
    /**
     * Get binding context instance.
     * @return IoContextInterface
     */
    public function getBoundContext(): IoContextInterface;

    /**
     * Set http header.
     * @param string $header
     * @param string $value
     * @param bool $ucwords
     * @return ResponseInterface
     */
    public function header(string $header, string $value, bool $ucwords = true): ResponseInterface;

    /**
     * Add value to http header.
     * @param string $header
     * @param string $value
     * @param bool $ucwords
     * @return ResponseInterface
     */
    public function withHeader(string $header, string $value, bool $ucwords = true): ResponseInterface;

    /**
     * Set http response status.
     * @param int|null $status_code
     * @param string $reason
     * @return ResponseInterface
     */
    public function status(?int $status_code = null, string $reason = ''): ResponseInterface;

    /**
     * Set http response charset of content.
     * @param string $charset
     * @return ResponseInterface
     */
    public function charset(string $charset): ResponseInterface;

    /**
     * Set use gzip to compress content of http.
     * @param int $level
     * @return ResponseInterface
     */
    public function gzip(int $level = -1): ResponseInterface;

    /**
     * Set cookie.
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $http_only
     * @param bool $is_raw
     * @return ResponseInterface
     */
    public function cookie(
        string $name,
        string $value = '',
        int $expire = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $http_only = false,
        $is_raw = false
    ): ResponseInterface;

    /**
     * Chunk content to response.
     * @param string $content
     * @return ResponseInterface
     */
    public function chunk(string $content): ResponseInterface;

    /**
     * Set content of response
     * @param string|null $content
     * @return ResponseInterface
     */
    public function content(?string $content): ResponseInterface;

    /**
     * Set json content type header with content.
     * @param $content
     * @return ResponseInterface
     */
    public function json($content): ResponseInterface;

    /**
     * Flush and finish response.
     * @return mixed
     */
    public function send(): ResponseInterface;

    /**
     * Redirect http location to url.
     * @param string $url
     * @param int $status_code
     * @return mixed
     */
    public function redirect(string $url, $status_code = 302);

    /**
     * Check current response if finish.
     * @return bool
     */
    public function isFinishSend(): bool;
}