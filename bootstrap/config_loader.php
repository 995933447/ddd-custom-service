<?php
use Infrastructure\Shared\Config\Config;

return function (array $config_dirs, string $file_ext = '') {
    if (!empty($file_ext)) {
        $file_ext = ".$file_ext";
    }

    foreach ($config_dirs as $config_dir) {
        foreach (glob($config_dir = rtrim($config_dir, '/') . "/*$file_ext") as $path_name) {
            if (is_file($path_name)) {
                $file_name = explode('.', basename($path_name))[0];
                Config::load($file_name, require $path_name);
            }
        }
    }
};