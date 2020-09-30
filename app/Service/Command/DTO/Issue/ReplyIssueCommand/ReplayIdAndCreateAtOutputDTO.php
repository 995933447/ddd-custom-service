<?php
namespace App\Service\Command\DTO\Issue\ReplyIssueCommand;

use App\Service\AbstractDTO;

class ReplayIdAndCreateAtOutputDTO extends AbstractDTO
{
    /**
     * @var string
     */
    protected $addtime;

    /**
     * @var int
     */
    protected $lastResponseId;

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

    /**
     * @return int
     */
    public function getLastResponseId(): int
    {
        return $this->lastResponseId;
    }

    /**
     * @param int $lastResponseId
     */
    public function setLastResponseId(int $last_response_id): void
    {
        $this->lastResponseId = $last_response_id;
    }
}