<?php
namespace Framework\AbstractInterface\Event;

interface EventDispatcherInterface
{
    public static function dispatch(AbstractEvent $event, array $arguments = []): void;
}