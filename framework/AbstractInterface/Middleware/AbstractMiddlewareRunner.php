<?php
namespace Framework\AbstractInterface\Middleware;

abstract class AbstractMiddlewareRunner
{
    protected $middleware = [];

    /**
     * Add Middleware.
     * @param array $middleware
     */
    public function addMiddleware(array $middleware): AbstractMiddlewareRunner
    {
        $this->middleware = array_merge($this->middleware, $this->normalizeMiddleware($middleware));

        return $this;
    }

    /**
     * Check and format Middleware argument.
     * @param array $middleware
     * @return array
     */
    protected function normalizeMiddleware(array $middleware): array
    {
        return array_map(function ($singleMiddleware) {
            if (is_array($singleMiddleware)) {
                if (!isset($singleMiddleware['handler'])) {
                    throw new \InvalidArgumentException(
                        "Invalid Middleware argument:" . var_export($singleMiddleware, true) .
                        ", valid argument['handler' => Object instance of" . AbstractMiddlewareHandler::class . ", 'argument' => mixed]"
                    );
                }
            } else {
                $singleMiddleware = ['handler' => $singleMiddleware];
            }

            return $this->makeMiddleware($singleMiddleware['handler'], $singleMiddleware['argument']?? null);
        }, $middleware);
    }

    abstract protected function makeMiddleware($handler, $argument): MiddlewareInterface;

    /**
     * Run Middleware though process then run process.
     * @param \Closure $process
     * @return mixed
     */
    abstract public function process(\Closure $process);
}