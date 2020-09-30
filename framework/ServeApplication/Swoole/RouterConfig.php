<?php
namespace Framework\ServeApplication\Swoole;

class RouterConfig implements RouterConfigInterface
{
    use ConfigArrayRequiredItemValidatorTrait;

    protected $routerHandler;

    protected $routerHandlerConstructor = [];

    protected $routeFile;

    protected $configArrayRequiredItems = [
        'handler', 'constructor', 'file'
    ];

    public function __construct(array $config_array)
    {
        $this->parse($config_array);
    }

    public function parse(array $config_array)
    {
        $this->validateRequiredItemsFromConfigArray($config_array, 'config');

        if (!is_array($config_array['constructor'])) {
            throw new \InvalidArgumentException('Router config[constructor] must be array.');
        }

        $this->routerHandlerConstructor = $config_array['constructor'];

        if (!is_string($config_array['file']) || !file_exists($config_array['file'])) {
            throw new \InvalidArgumentException('Router config[file] must be a valid path.');
        }

        $this->routeFile = $config_array['file'];

        $this->routerHandler = $config_array['handler'];
    }

    public function getRouterHandler()
    {
        return $this->routerHandler;
    }

    public function getConstructor(): array
    {
        return $this->routerHandlerConstructor;
    }

    public function getRouteFile(): string
    {
        return $this->routeFile;
    }
}