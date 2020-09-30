<?php
namespace Domain\Service;

use Domain\DomainServeException;
use Domain\DomainServiceInterface;
use Domain\Model\Issue\IssueProcessingProgress;
use Domain\Model\Issue\IssueRepository;
use Domain\Model\IssueReply\IssueReply;
use Domain\Model\IssueReply\IssueReplyRepository;
use Domain\Model\IssueReply\ReplyIssueAuthor;
use Domain\Model\IssueReply\ReplyIssueAuthorRole;
use Domain\Model\IssueReply\IssueReplyContent;
use Domain\Model\User\UserRepository;

class UserReplayIssueService implements DomainServiceInterface
{
    protected $userRepository;

    protected $issueRepository;

    protected $issueReplyRepository;

    protected $userId;

    protected $issueId;

    protected $content;

    protected $contentType;

    public function __construct(
        UserRepository $user_repository,
        IssueRepository $issue_repository,
        IssueReplyRepository $issue_reply_repository,
        int $user_id,
        int $issue_id,
        IssueReplyContent $content
    )
    {
        $this->userRepository = $user_repository;
        $this->issueRepository = $issue_repository;
        $this->issueReplyRepository = $issue_reply_repository;
        $this->userId = $user_id;
        $this->issueId = $issue_id;
        $this->content = $content;
    }

    public function execute(): IssueReply
    {
        if (is_null($user = $this->userRepository->existsById($this->userId))) {
            throw new DomainServeException('用户不存在.');
        }

        if (is_null($issue = $this->issueRepository->findAndLockById($this->issueId))) {
            throw new DomainServeException('工单不存在.');
        }

        if (!$issue->mayReply()) {
            throw new DomainServeException('该工单状态下无法回复.');
        }

        $issue_replay = IssueReply::create(
            $issue->getId(),
            $this->content,
            new ReplyIssueAuthor(ReplyIssueAuthorRole::beUser(), $this->userId),
            new \DateTimeImmutable()
        );

        if ($issue->isNotDeal()) {
            $issue->updateProcessProgress(IssueProcessingProgress::beDealing());
        }

        $this->issueRepository->save($issue);
        $this->issueReplyRepository->save($issue_replay);

        return $issue_replay;
    }
}