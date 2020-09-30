<?php
namespace Framework\Event;

use Framework\AbstractInterface\Event\EventDispatcherInterface;
use Framework\AbstractInterface\Event\AbstractEvent;
use Framework\AbstractInterface\Event\AbstractListener;

class EventDispatcher implements EventDispatcherInterface
{
    public static function dispatch(AbstractEvent $event, array $arguments = []): void
    {
        foreach ($event->getListeners() as $listener) {
            $listener = new $listener;

            if (!$listener instanceof AbstractListener) {
                throw new \RuntimeException(get_class($listener) . " not instance of " . AbstractListener::class);
            }

            $listener->execute($event, $arguments);
        }
    }
}