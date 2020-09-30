<?php
namespace App\Service;

use Shared\Formatter\StringHelper;

abstract class AbstractDTO
{
    protected $toArray;

    public function __construct(array $data)
    {
        $this->injectData($data);
    }

    protected function injectData(array $data): void
    {
        foreach ($data as $name => $value) {
            if (method_exists($this, $setter = 'set' . ucfirst(StringHelper::underlineToCamel($name)))) {
                $this->$setter($value);
            }
        }
    }

    public function toArray(): array
    {
        if (!empty($this->toArray)) {
            return $this->toArray;
        }

        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            if ($property->getName() == 'toArray') {
                continue;
            }

            $property->setAccessible(true);
            $this->toArray[StringHelper::humpToUnderline($property->getName())] = $property->getValue($this);
        }

        return $this->toArray;
    }
}