<?php
namespace App\Service\Query\DTO\Issue\IssueQuery;

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
     * @param int $issueId
     */
    public function setIssueId(int $issue_id): void
    {
        $this->issueId = $issue_id;
    }
}