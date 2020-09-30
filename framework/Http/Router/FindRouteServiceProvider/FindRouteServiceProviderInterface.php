<?php
namespace Framework\Http\Router\FindRouteServiceProvider;

use Framework\AbstractInterface\Http\Router\RouterInterface;

interface FindRouteServiceProviderInterface
{
    public function handle(RouterInterface $router);
}