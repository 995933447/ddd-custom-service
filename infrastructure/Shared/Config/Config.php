<?php

namespace Infrastructure\Shared\Config;

use RuntimeException;

/**
 * Class Config
 * @package HttpServeApplication
 * 配置文件读取类，只支持2维数组，超过2维，仅返回原始数组
 */
class Config
{
    private static $config = [];

    /**
     * @param $name
     * @throws RuntimeException
     */
    public static function load($name, array $options)
    {
        self::$config[$name] = array_merge(self::$config[$name]?? [], $options);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public static function get($name)
    {
        $config = null;
        switch (substr_count($name, '.')) {
            case 0:
                $config = self::$config[$name];
                break;
            case 1:
                list($scope, $name) = explode('.', $name, 2);
                $config = self::$config[$scope][$name];
                break;
            case 2:
                list($scope, $name, $sub_name) = explode('.', $name, 3);
                $config = self::$config[$scope][$name][$sub_name];
                break;
        }
        return $config;
    }
}