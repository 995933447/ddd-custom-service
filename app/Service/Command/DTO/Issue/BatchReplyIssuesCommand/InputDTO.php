<?php
namespace App\Service\Command\DTO\Issue\BatchReplyIssuesCommand;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var array
     */
    protected $issueIds;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var bool
     */
    protected $isEndDealIssues;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @return array
     */
    public function getIssueIds(): array
    {
        return $this->issueIds;
    }

    /**
     * @param array $issue_ids
     */
    public function setIssueIds(array $issue_ids): void
    {
        $this->issueIds = $issue_ids;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function getIsEndDealIssues(): bool
    {
        return $this->isEndDealIssues;
    }

    /**
     * @param bool $end_deal_issue
     */
    public function setIsEndDealIssues(bool $is_end_deal_issues): void
    {
        $this->isEndDealIssues = $is_end_deal_issues;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->userId = $user_id;
    }
}