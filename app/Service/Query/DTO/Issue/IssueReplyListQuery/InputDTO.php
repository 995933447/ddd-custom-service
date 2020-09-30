<?php
namespace App\Service\Query\DTO\Issue\IssueReplyListQuery;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var int
     */
    protected $issueId;

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
}