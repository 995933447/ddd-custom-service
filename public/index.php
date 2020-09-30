<?php
require __DIR__ . '/../vendor/autoload.php';

/**
 * Load global config.
 */
$config_loader = require __DIR__ . '/../bootstrap/config_loader.php';
$config_loader([
        __DIR__ . '/../infrastructure/resource'
], 'php');

/**
 * Set default time zone
 */
$default_time_zone_setter = require __DIR__ . '/../bootstrap/default_time_zone_setter.php';
$default_time_zone_setter();

/**
 * Set catch exception handler.
 */
$exception_handler = require __DIR__ . '/../bootstrap/http_exception_handler.php';
set_exception_handler($exception_handler);

/**
 * Crate middleware factory.
 */
$middleware_factory = require __DIR__ . '/../bootstrap/middleware_factory.php';

/**
 * Create IO context singleton instance.
 */
$io_context = \App\Http\IO\DefaultIOContextFactory::get();
$io_context->setFastcgiMode();

/**
 * Build http router.
 */
$router_builder = require __DIR__ . '/../bootstrap/http_router_builder.php';
$router = $router_builder($io_context, $middleware_factory([]), 'html', 'portal', 'index', 'App\\Http\\Controller\\');

/**
 * Init global DB.
 */
$do_bootstrapper = require __DIR__ . '/../bootstrap/data_object_bootstrapper.php';
$do_bootstrapper();

/**
 * Run framework and response http request.
 */
$response = (
    new \Framework\ServeApplication\Fastcgi\Application(
        $router,
        $middleware_factory([
            \App\Http\Middleware\CORSMiddlewareMiddlewareHandler::class
        ])
    )
)->handle($io_context->getRequest());

if (!$response->isFinishSend()) {
    $response->send();
}