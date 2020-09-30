<?php
namespace Domain\Model\IssueReply;

interface IssueReplyRepository
{
    public function save(IssueReply $issue_reply): void;
}