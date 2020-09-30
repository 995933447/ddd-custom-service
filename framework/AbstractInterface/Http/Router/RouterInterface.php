<?php
namespace Framework\AbstractInterface\Http\Router;

use Framework\AbstractInterface\Http\Server\RequestInterface;
use Framework\AbstractInterface\Http\Server\ResponseInterface;
use Framework\Exception\Http\Router\NotAllowMethodHttpException;
use Framework\Exception\Http\Router\NotFoundHttpException;

interface RouterInterface
{
    /**
     * Add route finder from custom.
     * @param string $finder_name
     * @param $route_finder
     */
    public function addRouteFinder(string $finder_name, $route_finder);

    /**
     * Set use which route finder.
     * @param string $finder_name
     * @param array $finder_options
     * @return $this
     */
    public function setRouteFinder(string $finder_name, array $finder_options): RouterInterface;

    /**
     * Found destination route.
     * @param RequestInterface $request
     * @return RouteInterface
     * @throws NotFoundHttpException|NotAllowMethodHttpException
     */
    public function findRoute(RequestInterface $request): RouteInterface;

    /**
     * Let route run to process.
     * @param RouteInterface $route
     */
    public function runRoute(RouteInterface $route): ResponseInterface;
}