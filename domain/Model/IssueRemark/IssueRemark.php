<?php
namespace Domain\Model\IssueRemark;

use Domain\AbstractAggregateRoot;

class IssueRemark extends AbstractAggregateRoot
{
    protected $id;

    protected $issueId;

    protected $content;

    protected $createdAt;

    protected $userId;

    public function __construct(?int $id, int $issue_id, string $content, \DateTimeImmutable $createdAt, int $user_id)
    {
        $this->id = $id;
        $this->setIssueId($issue_id);
        $this->setContent($content);
        $this->createdAt = $createdAt;
        $this->setUserId($user_id);
    }

    public static function create(int $issue_id, string $content, \DateTimeImmutable $createdAt, int $user_id): self
    {
        return new static(null, $issue_id, $content, $createdAt, $user_id);
    }

    protected function setUserId(int $user_id): void
    {
        if ($user_id < 0) {
            throw new \InvalidArgumentException();
        }

        $this->userId = $user_id;
    }

    protected function setContent(string $content): void
    {
        if (empty($content)) {
            throw new \InvalidArgumentException();
        }

        $this->content = $content;
    }

    protected function setIssueId(int $issue_id): void
    {
        if ($issue_id <= 0) {
            throw new \InvalidArgumentException();
        }

        $this->issueId = $issue_id;
    }

    /**
     * @return int
     */
    public function setId(int $id)
    {
        if ($id < 0) {
            throw new \InvalidArgumentException();
        }

        $this->id = $id;
    }

    public function getIssueId(): int
    {
        return $this->issueId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}