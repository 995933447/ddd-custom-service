<?php
namespace Domain\Model\Issue;

use Domain\AbstractValueObject;

class MachineNetwork extends AbstractValueObject
{
    const KDDI_NETWORK = 1;

    const UNICOM_NETWORK = 2;

    const CMCC_NETWORK = 3;

    const WIFI_NETWORK = 4;

    protected $type;

    protected function __construct(int $type)
    {
        $this->type = $type;
    }

    public static function beKDDI(): self
    {
        return new static(static::KDDI_NETWORK);
    }

    public static function beUNICOM(): self
    {
        return new static(static::UNICOM_NETWORK);
    }

    public static function beCMCC(): self
    {
        return new static(static::CMCC_NETWORK);
    }

    public static function beWifi(): self
    {
        return new self(static::WIFI_NETWORK);
    }

    public function equalsTo(self $network): bool
    {
        return $this->type === $network->type;
    }

    public function isKDDINetwork(): bool
    {
        return $this->equalsTo(static::beKDDI());
    }

    public function isUNICOMNetwork(): bool
    {
        return $this->equalsTo(static::beUNICOM());
    }

    public function isCMCCNetwork(): bool
    {
        return $this->equalsTo(static::beCMCC());
    }

    public function isWifiNetwork(): bool
    {
        return $this->equalsTo(static::beWifi());
    }
}