<?php
namespace Framework\ServeApplication\Swoole;

use InvalidArgumentException;

trait ConfigArrayRequiredItemValidatorTrait
{
    public function validateRequiredItemsFromConfigArray(array $config_array, string $config_name = '')
    {
        array_walk($this->configArrayRequiredItems, function ($item) use ($config_array, $config_name) {
            if (!isset($config_array[$item]) || is_null($config_array[$item])) {
                throw new InvalidArgumentException(ucfirst($config_name) . "config[$item] not set.");
            }
        });
    }
}