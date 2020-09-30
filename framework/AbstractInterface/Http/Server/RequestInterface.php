<?php
namespace Framework\AbstractInterface\Http\Server;

interface RequestInterface
{
    /**
     * Fetch raw data.
     * @return [type]               [description]
     */
    public function PHPInput();

    /**
     * Fetch data from http request.
     * Fetch http
     * @return mixed
     */
    public function input(string $name = null, $default_value = null);

    /**
     * Fetch http get data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function get(string $name = null, $default_value = null);

    /**
     * Fetch http post data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function post(string $name = null, $default_value = null);

    /**
     * Fetch http file data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function files(string $name = null, $default_value = null);

    /**
     * Fetch http cookie.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function cookie(string $name = null, $default_value = null);

    /**
     * Fetch RouterInterface environment data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function server(string $name = null, $default_value = null);

    /**
     * Fetch http header.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function header(string $name = null);

    /**
     * Fetch uri.
     * @return [type] [description]
     */
    public function uri(): string;

    /**
     * Fetch uri with query.
     * @return [type] [description]
     */
    public function uriWithQuery(): string;

    /**
     * Fetch http method.
     * @return [type] [description]
     */
    public function method(): string;

    /**
     * Fetch request time.
     * @return [type] [description]
     */
    public function time();

    /**
     * Get binding context instance.
     * @return IoContextInterface
     */
    public function getBoundContext(): IoContextInterface;
}