<?php
namespace Framework\Http\Server\Request;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Swoole\Http\Request;

/**
 * http(swoole模式)请求封装组件
 */
class SwooleRequest extends AbstractRequest
{
    protected $rawRequest;

    protected $header;

    public function __construct(IoContextInterface $context, Request $raw_request)
    {
        $this->rawRequest = $raw_request;

        parent::__construct($context);
    }

    public function load(string $parameters_type): array
    {
        $parameters = [];

        switch ($parameters_type) {
            case 'server':
                $server = [];
                foreach ($_SERVER as $key => $value) {
                    $server[strtolower($key)] = $value;
                }
                $parameters = array_merge($server, $this->rawRequest->$parameters_type);
                break;
            case 'input':
                $parameters = array_merge($this->get(), $this->post());
                break;
            case 'post':
                if ($this->header('content-type') === 'application/json') {
                    $parameters = json_decode($this->PHPInput(), true);
                    break;
                }
            default:
                $parameters = $this->rawRequest->$parameters_type;
        }

        return $parameters;
    }

    public function PHPInput()
    {
        if (!$this->PHPInput) $this->PHPInput = $this->rawRequest->rawContent();

       return $this->PHPInput;
    }

    public function header(string $name = null)
    {
        $this->loadFrom('header', $name);
    }
}