<?php
namespace Domain\Model\Game;

use Domain\AbstractAggregateRoot;

class Game extends AbstractAggregateRoot
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    protected $version;

    public function __construct(int $id, string $name, string $version)
    {
        $this->id = $id;
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}