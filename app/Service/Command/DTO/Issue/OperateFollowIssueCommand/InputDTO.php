<?php
namespace App\Service\Command\DTO\Issue\OperateFollowIssueCommand;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var int
     */
    protected $issueId;

    /**
     * @var bool
     */
    protected $isFollowed;

    /**
     * @return int
     */
    public function getIssueId(): int
    {
        return $this->issueId;
    }

    /**
     * @param int $issue_id
     */
    public function setIssueId(int $issue_id): void
    {
        $this->issueId = $issue_id;
    }

    /**
     * @return bool
     */
    public function getIsFollowed(): bool
    {
        return $this->isFollowed;
    }

    /**
     * @param bool $is_followed
     */
    public function setIsFollowed(bool $is_followed): void
    {
        $this->isFollowed = $is_followed;
    }
}