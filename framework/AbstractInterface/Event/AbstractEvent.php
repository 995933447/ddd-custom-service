<?php
namespace Framework\AbstractInterface\Event;

abstract class AbstractEvent{
    abstract public function getListeners(): array;
}