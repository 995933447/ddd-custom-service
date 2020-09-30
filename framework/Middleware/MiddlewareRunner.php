<?php
namespace Framework\Middleware;

use Closure;
use Framework\AbstractInterface\Middleware\MiddlewareInterface;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareHandler;

/**
 * Runner of Middleware with process.
 * Class MiddlewareRunner
 * @package
 */
class MiddlewareRunner extends AbstractMiddlewareRunner
{
    protected $middleware = [];

    /**
     * Run Middleware though process then run process.
     * @param Closure $process
     * @return mixed
     */
    public function process(Closure $process)
    {
        $wrapMiddlewareIntoProcess = $this->wrapMiddlewareOnProcess($process);

        return call_user_func($wrapMiddlewareIntoProcess);
    }

    /**
     * Merge Middleware with process as onion model.
     * @param Closure $process
     * @return Closure
     */
    protected function wrapMiddlewareOnProcess(Closure $process): Closure
    {
        return array_reduce(array_reverse($this->middleware), function ($next, MiddlewareInterface $singleMiddleware) {
            return function () use ($next, $singleMiddleware) {
                $arguments = [$next, $singleMiddleware->getArgument()];
                $handler = $singleMiddleware->getHandler();
                return $handler->handle(...$arguments);
            };
        }, $process);
    }

    protected function makeMiddleware($handler, $argument): MiddlewareInterface
    {
        return new Middleware($handler, $argument);
    }
}
