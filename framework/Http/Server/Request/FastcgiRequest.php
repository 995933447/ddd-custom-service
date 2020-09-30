<?php
namespace Framework\Http\Server\Request;

use Framework\AbstractInterface\Http\Server\IoContextInterface;

class FastcgiRequest extends AbstractRequest
{
    protected $header;

    public function __construct(IoContextInterface $context)
    {
        parent::__construct($context);
    }

    public function load(string $parameters_type): array
    {
        $parameters = [];

        switch ($parameters_type) {
            case 'server':
                if ('cli-server' === PHP_SAPI) {
                    if (isset($_SERVER['HTTP_CONTENT_LENGTH'])) {
                        $_SERVER['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
                        unset($_SERVER['HTTP_CONTENT_LENGTH']);
                    }

                    if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
                        $_SERVER['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
                        unset($_SERVER['HTTP_CONTENT_TYPE']);
                    }
                }
                $parameters = $_SERVER;
                break;
            case 'get':
                $parameters = $_GET;
                break;
            case 'post':
                if (strpos($this->header('content-type'), 'application/json') === 0 ) {
                    $parameters = json_decode($this->PHPInput(), true);
                } else {
                    $parameters = $_POST;
                }
                break;
            case 'files':
                $parameters = $_FILES;
                break;
            case 'cookie':
                $parameters = $_COOKIE;
                break;
            case 'header':
                $parameters = $this->server();
                break;
            case 'input':
                $parameters = array_merge($this->get(), $this->post());
        }

        foreach ($parameters as $key => $value) {
            unset($parameters[$key]);

            switch ($parameters_type) {
                case 'server':
                    $key = strtolower($key);
                    break;
                case 'header':
                    if (strpos($key, 'http_')) {
                        $key = substr($key, 5);
                    }
                    $key = str_replace('_', '-', strtolower($key));
            }

            $parameters[$key] = $value;
        }

        return $parameters;
    }

    public function PHPInput()
    {
        if (!$this->PHPInput) $this->PHPInput = file_get_contents('php://input');

        return $this->PHPInput;
    }

    public function header(string $name = null)
    {
        if (is_string($name)) {
            $name = strtolower($name);
        }
        return $this->loadFrom('header', $name);
    }
}