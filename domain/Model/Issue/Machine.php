<?php
namespace Domain\Model\Issue;

use Domain\AbstractValueObject;

class Machine extends AbstractValueObject
{
    protected $model;

    protected $os;

    protected $brand;

    protected $network;

    public function __construct(string $model, MachineOs $os, string $brand, ?MachineNetwork $network)
    {
        $this->model = $model;
        $this->os = $os;
        $this->brand = $brand;
        $this->network = $network;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return MachineOs
     */
    public function getOs(): MachineOs
    {
        return $this->os;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getNetwork(): ?MachineNetwork
    {
        return $this->network;
    }

    public function equalsTo(self $machine): bool
    {
        return $this->getModel() === $machine->getModel() &&
            $this->getBrand() === $machine->getBrand() &&
            $this->getOs()->equalsTo($machine->getOs()) &&
            ((is_null($this->getNetwork()) && is_null($machine->getNetwork()))
                || $this->getNetwork()->equalsTo($machine->getNetwork()));
    }
}