<?php
namespace Framework\Http\Router\Route\ControllerDestinationRoute;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\AbstractInterface\Http\Server\ResponseInterface;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareRunner;
use Framework\Exception\Http\Router\NotFoundHttpException;
use Framework\AbstractInterface\Http\Router\RouteInterface;

class ControllerDestinationRoute implements RouteInterface
{
    protected $controllerNamespace;

    protected $destinationController;

    protected $destinationAction;

    protected $routeMiddlewareRunner;

    protected $IOContext;

    public function __construct(
        IoContextInterface $io_context,
        AbstractMiddlewareRunner $routeMiddlewareRunner,
        string $destination_controller = null,
        string $destination_action = null,
        string $controller_namespace = null
    )
    {
        $this->IOContext = $io_context;
        $this->routeMiddlewareRunner = $routeMiddlewareRunner;

        if (!is_null($destination_controller)) {
            $this->setDestinationController($destination_controller);
        }

        if (!is_null($destination_action)) {
            $this->setDestinationAction($destination_action);
        }

        if (!is_null($controller_namespace)) {
            $this->setControllerNamespace($controller_namespace);
        }
    }

    public function setDestinationController(string $destination_controller)
    {
        $this->destinationController = ucfirst($destination_controller) . "Controller";
    }

    public function setDestinationAction(string $destination_action)
    {
        $this->destinationAction = $destination_action;
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->controllerNamespace = $namespace;
    }

    public function hasDestination(): bool
    {
        return !is_null($this->getDestinationController()) && !is_null($this->getDestinationAction());
    }

    public function runToProcess(): ResponseInterface
    {
        if (!$this->hasDestination()) {
            throw new NotFoundHttpException();
        }

        if (!class_exists($destination_controller = $this->getDestinationController())) {
            throw new NotFoundHttpException("Controller:$destination_controller not found.");
        }

        if (!method_exists($destination_controller, $destination_action = $this->getDestinationAction())) {
            throw new NotFoundHttpException("Action: $destination_action of Controller: $destination_controller not found.");
        }

        return $this->dispatchToController();
    }

    protected function getDestinationController()
    {
        return $this->controllerNamespace . $this->destinationController;
    }

    protected function getDestinationAction()
    {
        return $this->destinationAction;
    }

    public function dispatchToController(): ResponseInterface
    {
        return ControllerDispatcher::dispatch(
            $this->getDestinationController(),
            $this->getDestinationAction(),
            $this->routeMiddlewareRunner,
            $this->IOContext
        );
    }
}