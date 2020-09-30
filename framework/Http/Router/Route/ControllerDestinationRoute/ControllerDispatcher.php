<?php
namespace Framework\Http\Router\Route\ControllerDestinationRoute;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Http\Server\ResponseInterface;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;

/**
 * Dispatch controller with action of route's destination.
 * Class ControllerDispatcher
 * @package Framework\Http\RouterInterface\Route\ControllerDestinationRoute
 */
class ControllerDispatcher
{
    public static function dispatch(
        string $controller_name,
        string $action_name,
        AbstractMiddlewareRunner $route_middleware_runner,
        IoContextInterface $io_context
    ): ResponseInterface
    {
        $controller = new $controller_name;
        $controller_reflector = new \ReflectionClass($controller);

        $controller_middleware = [];
        if ($controller_reflector->hasProperty($middleware_property = 'middleware')) {
            $middleware_property_reflector = $controller_reflector->getProperty($middleware_property);
            $middleware_property_reflector->setAccessible(true);
            $controller_middleware = $middleware_property_reflector->getValue($controller)?: [];
        }

        $normalized_middleware = static::normalizeControllerMiddleware($controller_middleware, $action_name);

        return $route_middleware_runner->addMiddleware($normalized_middleware)
            ->process(function () use ($controller, $action_name, $io_context) {
                return static::toResponse($controller->$action_name(), $io_context);
            });
    }

    protected static function toResponse($data, IoContextInterface $io_context): ResponseInterface
    {
        if (!$data instanceof ResponseInterface) {
            if (!is_null($data) && !is_string($data)) {
                $response = $io_context->getResponse()->json($data);
            } else {
                $response = $io_context->getResponse()->content($data);
            }
        } else {
            $response = $data;
        }

        return $response;
    }

    protected static function normalizeControllerMiddleware(
        array $controller_middleware,
        string $action_name
    ): array
    {
        $normalized_middleware = [];

        foreach ($controller_middleware as $middleware => $options) {
            if (is_int($middleware)) {
                $normalized_middleware[] = $options;
                continue;
            }

            if (isset($options['only'])) {
                if (in_array($action_name, $options['only'])) {
                    $normalized_middleware[] = isset($options['argument'])? ['handler' => $middleware, 'argument' => $options['argument']]:$middleware;
                    continue;
                }
            }

            if (isset($options['exclude'])) {
                if (!in_array($action_name, $options['exclude'])) {
                    $normalized_middleware[] = isset($options['argument'])? ['handler' => $middleware, 'argument' => $options['argument']]:$middleware;
                }
            }
        }

        return $normalized_middleware;
    }
}