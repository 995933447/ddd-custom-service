<?php
namespace App\Service\Query\DTO\Issue\IssueOfUserQuery;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var int
     */
    protected $excludeIssueId;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @return int
     */
    public function getExcludeIssueId(): int
    {
        return $this->excludeIssueId;
    }

    /**
     * @param int $issue_id
     */
    public function setExcludeIssueId(int $issue_id): void
    {
        $this->excludeIssueId = $issue_id;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $user_name
     */
    public function setUserName(string $user_name): void
    {
        $this->userName = $user_name;
    }

}