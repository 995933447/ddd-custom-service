<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

/**
 * Load global config.
 */
$config_loader = require __DIR__ . '/../bootstrap/config_loader.php';
$config_loader([
    __DIR__ . '/../infrastructure/resource'
], 'php');

/**
 * Create command application instance.
 */
$application = new Application();

/**
 * Add command routes.
 */
$command_routes_loader = require __DIR__ . '/../bootstrap/command_routes_loader.php';
$command_routes_loader($application, [
    __DIR__ . '/../route/command.php'
]);

/**
 * Run command application.
 */
$application->run();