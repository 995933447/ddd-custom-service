<?php
namespace Framework\ServeApplication\Fastcgi;

use Framework\AbstractInterface\Http\Router\RouteInterface;
use Framework\AbstractInterface\Http\Router\RouterInterface;
use Framework\AbstractInterface\Http\Server\RequestInterface;
use Framework\AbstractInterface\Http\Server\ResponseInterface;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;

/** Run application framework.
 * Class HttpServeApplication
 * @package Framework
 */
class Application
{
    protected $router;

    protected $middlewareRunner;

    public function __construct(RouterInterface $router, AbstractMiddlewareRunner $middleware_runner)
    {
        $this->router = $router;
        $this->middlewareRunner = $middleware_runner;
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        $route = $this->findRoute($request);

        return static::toResponse(
            $this->middlewareRunner->process(function () use ($route) {
                return $this->router->runRoute($route);
            }),
            $request
        );
    }

    protected static function toResponse($data, RequestInterface $request): ResponseInterface
    {
        if (!$data instanceof ResponseInterface) {
            if (!is_null($data) && !is_string($data)) {
                $response = $request->getBoundContext()->getResponse()->json($data);
            } else {
                $response = $request->getBoundContext()->getResponse()->content($data);
            }
        } else {
            $response = $data;
        }

        return $response;
    }

    protected function findRoute(RequestInterface $request): ?RouteInterface
    {
        return $this->router->findRoute($request);
    }
}