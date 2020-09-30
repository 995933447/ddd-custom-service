<?php
namespace App\Service\Query\DTO\Issue\IssueQuickReplyListQuery;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var int
     */
    protected $gameId;

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     */
    public function setGameId(int $game_id): void
    {
        $this->gameId = $game_id;
    }
}