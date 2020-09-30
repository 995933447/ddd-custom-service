<?php
namespace Framework\AbstractInterface\Http\Server;

use Swoole\Http\Request;
use Swoole\Http\Response;

interface IoContextInterface
{
    /**
     * Reset current http context to empty state.
     * @return mixed
     */
    public function resetToEmptyContext();

    /**
     * Set current http context as swoole mode.
     * @param $swoole_raw_request
     * @param $swoole_raw_response
     * @return IoContextInterface
     */
    public function setSwooleMode(Request $swoole_raw_request, Response $swoole_raw_response): IoContextInterface;

    /**
     * Set current http context as fastcgi mode, like fpm.
     * @return IoContextInterface
     */
    public function setFastcgiMode(): IoContextInterface;

    /**
     * Check current http context if swoole mode.
     * @return bool
     */
    public function isSwooleMode(): bool;

    /**
     * Check current http context if fastcgi mode.
     * @return bool
     */
    public function isFastcgiMode(): bool;

    /**
     * Get request singleton instance.
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;

    /**
     * Get response singleton instance.
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;

    /**
     * Get response singleton instance.
     * @return ResponseInterface
     */
    public function makeResponse(): ResponseInterface;

    /**
     * Create and save a new request instance from current http context.
     * @return RequestInterface
     */
    public function reloadRequest(): RequestInterface;
}