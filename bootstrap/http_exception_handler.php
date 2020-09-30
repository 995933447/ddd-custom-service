<?php
use Infrastructure\Shared\Config\Config;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

return function ($e) {
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');

    if (Config::get('base.debug')) {
        try {
            $whoops = new Run;
            $whoops->pushHandler(new PrettyPageHandler);
            $whoops->register();
            $whoops->handleException($e);
        } catch (\Exception $e) {
            echo '['.date('Y-m-d h:i:s').']  ,error code: ' . $e->getCode() . ' error message:' . $e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine() . "\n" . $e->getTraceAsString();
        }
    } else {
        json_response([], $e->getCode(), $e->getMessage());
    }
};