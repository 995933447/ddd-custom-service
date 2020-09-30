<?php
use Symfony\Component\Console\Application;

return function (Application $application, array $route_files) {
    foreach ($route_files as $route_file) {
        require $route_file;
    }
};