<?php
namespace App\Service\Command\DTO\Issue\RemarkIssueCommand;

use App\Service\AbstractDTO;

class RemarkIdAndCreatedAtOutputDTO extends AbstractDTO
{
    /**
     * @var int
     */
    protected $lastNoteId;

    /**
     * @var string
     */
    protected $addtime;

    /**
     * @return int
     */
    public function getLastNoteId(): int
    {
        return $this->lastNoteId;
    }

    /**
     * @param int $lastNoteId
     */
    public function setLastNoteId(int $lastNoteId): void
    {
        $this->lastNoteId = $lastNoteId;
    }

    /**
     * @return string
     */
    public function getAddtime(): string
    {
        return $this->addtime;
    }

    /**
     * @param string $addtime
     */
    public function setAddtime(string $addtime): void
    {
        $this->addtime = $addtime;
    }
}