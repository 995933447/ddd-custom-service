<?php
namespace Infrastructure\Log;

use DateTimeZone;
use Infrastructure\Shared\Config\Config;
use Infrastructure\Shared\Log\AbstractLogger;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileLogger extends AbstractLogger
{
    public function __construct(string $name, array $handlers = [], array $processors = [], ?DateTimeZone $timezone = null)
    {
        parent::__construct($name, $handlers, $processors, $timezone);

        // 添加一些处理器
        $this->pushHandler(new StreamHandler(Config::get('log.path') . '/firebase.log', Logger::DEBUG));
        $this->pushHandler(new FirePHPHandler());
    }
}