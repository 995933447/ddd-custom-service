<?php
namespace Domain\Model\GameRole;

use Domain\AbstractAggregateRoot;

class GameRole extends AbstractAggregateRoot
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $gameId;

    /**
     * @var string
     */
    protected $name;

    public function __construct(int $id, int $game_id, $name)
    {
        $this->id = $id;
        $this->gameId = $game_id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}