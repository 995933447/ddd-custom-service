<?php
namespace Domain\Model\IssueReply;

use Domain\AbstractEntity;

class ReplyIssueAuthor extends AbstractEntity
{
    /**
     * @var ReplyIssueAuthorRole
     */
    protected $role;

    /**
     * @var int
     */
    protected $id;

    public function __construct(ReplyIssueAuthorRole $role, ?int $id)
    {
        $this->role = $role;

        $this->setId($id);
    }

    /**
     * @return ReplyIssueAuthorRole
     */
    public function getRole(): ReplyIssueAuthorRole
    {
        return $this->role;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
}