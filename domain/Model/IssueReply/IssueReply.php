<?php
namespace Domain\Model\IssueReply;

use Domain\AbstractAggregateRoot;

class IssueReply extends AbstractAggregateRoot
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var int
     */
    protected $issueId;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var ReplyIssueAuthor
     */
    protected $owner;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    public function __construct(?int $id, int $issue_id, IssueReplyContent $content, ReplyIssueAuthor $author, \DateTimeImmutable $created_at)
    {
        $this->id = $id;
        $this->setIssueId($issue_id);
        $this->content = $content;
        $this->owner = $author;
        $this->createdAt = $created_at;
    }

    public static function create(int $issue_id, IssueReplyContent $content, ReplyIssueAuthor $author, \DateTimeImmutable $created_at): self
    {
        return new static(null, $issue_id, $content, $author, $created_at);
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
    public function setId(int $id): void
    {
        if ($id < 0) {
            throw new \InvalidArgumentException();
        }

        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIssueId(): int
    {
        return $this->issueId;
    }

    /**
     * @return string
     */
    public function getContent(): IssueReplyContent
    {
        return $this->content;
    }

    /**
     * @return ReplyIssueAuthor
     */
    public function getAuthor(): ReplyIssueAuthor
    {
        return $this->owner;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}