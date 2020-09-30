<?php
namespace Domain\Model\Issue;

use Domain\AbstractValueObject;

class MachineOs extends AbstractValueObject
{
    const PC_OS = 1;

    const IOS_OS = 2;

    const ANDROID_OS = 3;

    const WAP_OS = 4;

    protected $type;

    protected $version;

    protected function __construct(int $type, string $version)
    {
        $this->type = $type;
        $this->version = $version;
    }

    public static function bePcOs(string $version): self
    {
        return new static(static::PC_OS, $version);
    }

    public static function beIosOs(string $version): self
    {
        return new static(static::IOS_OS, $version);
    }

    public static function beAndroidOs(string $version): self
    {
        return new static(static::ANDROID_OS, $version);
    }

    public static function beWapOs(string $version): self
    {
        return new static(static::WAP_OS, $version);
    }

    protected function equalsToOsType(int $type): bool
    {
        return $this->type === $type;
    }

    public function isPcOS(): bool
    {
        return $this->equalsToOsType(static::PC_OS);
    }

    public function isIosOS(): bool
    {
        return $this->equalsToOsType(static::IOS_OS);
    }

    public function isAndroidOs(): bool
    {
        return $this->equalsToOsType(static::ANDROID_OS);
    }

    public function isWapOs(): bool
    {
        return $this->equalsToOsType(static::WAP_OS);
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function equalsTo(self $os): bool
    {
        return $this->getVersion() === $os->getVersion() && $this->type === $os->type;
    }
}