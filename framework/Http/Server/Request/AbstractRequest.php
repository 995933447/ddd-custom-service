<?php
namespace Framework\Http\Server\Request;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Http\Server\RequestInterface;

abstract class AbstractRequest implements RequestInterface
{
    protected $server;

    protected $input;

    protected $post;

    protected $get;

    protected $files;

    protected $cookie;

    protected $PHPInput;

    protected $boundContext;

    /**
     * Bound context instance.
     * AbstractRequest constructor.
     * @param IoContextInterface $context
     */
    public function __construct(IoContextInterface $context)
    {
        $this->boundContext = $context;
    }

    /**
     * Get current context instance.
     * @return IoContextInterface
     */
    public function getBoundContext(): IoContextInterface
    {
        return $this->boundContext;
    }

    /**
     * Fetch http data.
     * @param $parameter_from
     * @param null $parameter
     * @return mixed
     */
    protected function loadFrom(string $parameter_from, string $parameter_name = null, $default_value = null)
    {
        if (is_null($this->$parameter_from)) {
            $this->$parameter_from = $this->load($parameter_from);
        }

        if (is_null($parameter_name)) {
            return $this->$parameter_from;
        }

        return $this->$parameter_from[$parameter_name]?? $default_value;
    }

    /**
     * Fetch data from http request.
     * @param string|null $name
     * @param null $default_value
     * @return mixed|void
     */
    public function input(string $name = null, $default_value = null)
    {
        return $this->loadFrom('input', $name, $default_value);
    }

    /**
     * Fetch http get data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function get(string $name = null, $default_value = null)
    {
        return $this->loadFrom('get', $name, $default_value);
    }

    /**
     * Fetch http post data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function post(string $name = null, $default_value = null)
    {
        return $this->loadFrom('post', $name, $default_value);
    }

    /**
     * Fetch http file data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function files(string $name = null, $default_value = null)
    {
        return $this->loadFrom('files', $name, $default_value);
    }

    /**
     * Fetch http cookie.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function cookie(string $name = null, $default_value = null)
    {
        return $this->loadFrom('cookie', $name, $default_value);
    }

    /**
     * Fetch RouterInterface environment data.
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function server(string $name = null, $default_value = null)
    {
        if (is_string($name)) {
            $name = strtolower($name);
        }
        return $this->loadFrom('server', $name, $default_value);
    }

    /**
     * Fetch uri.
     * @return [type] [description]
     */
    public function uri(): string
    {
        if ($path_info = $this->server('path_info')) {
            return $path_info;
        }

        if(strpos($uri = $this->server('request_uri'), $script = $this->server('php_self')) === 0) {
            $uri = mb_substr($uri, mb_strlen($script));
        }

        $uri = ($uri === '' || $uri[0] === '?') ? '/' : $uri;

        if($position = strpos($uri, '?') !== false) {
            return substr($uri, 0, $position);
        }

        return $uri;
    }

    /**
     * Fetch uri with query.
     * @return [type] [description]
     */
    public function uriWithQuery(): string
    {
        return $this->uri() . '?' . $this->server('query_string');
    }

    /**
     * Fetch http method.
     * @return [type] [description]
     */
    public function method(): string
    {
        return $this->server('request_method');
    }

    /**
     * Fetch request time.
     * @return [type] [description]
     */
    public function time()
    {
        return $this->server('request_time');
    }
}