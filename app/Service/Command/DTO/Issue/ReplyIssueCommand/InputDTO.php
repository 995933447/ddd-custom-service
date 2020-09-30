<?php
namespace App\Service\Command\DTO\Issue\ReplyIssueCommand;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var int
     */
    protected $issueId;

    /**
     * @var string
     */
    protected $content;

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
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $user_id): void
    {
        $this->userId = $user_id;
    }
}