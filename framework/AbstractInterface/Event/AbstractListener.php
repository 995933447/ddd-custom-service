<?php
namespace Framework\AbstractInterface\Event;

abstract class AbstractListener
{
    final public function execute(AbstractEvent $event, array $data)
    {
        if (!method_exists($this, 'handle')) {
            throw new \RuntimeException(get_class($this) . '::handle($event, array $data) not implements.');
        }

        $this->handle($event, $data);
    }
}