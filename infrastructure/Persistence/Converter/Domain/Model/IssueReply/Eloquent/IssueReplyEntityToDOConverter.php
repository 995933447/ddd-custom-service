<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\IssueReply\Eloquent;

use Domain\Model\IssueReply\IssueReply as IssueReplyEntity;
use Infrastructure\Persistence\Converter\DomainEntityToDOConverterInterface;
use Infrastructure\Persistence\DataObject\Eloquent\IssueReply as IssueReplyEloquent;
use Infrastructure\Persistence\DataObject\Eloquent\User as UserEloquent;

class IssueReplyEntityToDOConverter implements DomainEntityToDOConverterInterface
{
    protected $issueReplyEntity;

    public function __construct(IssueReplyEntity $issue_reply_entity)
    {
        $this->issueReplyEntity = $issue_reply_entity;
    }

    public function toDataObject()
    {
        $issue_replay_do = new IssueReplyEloquent();

        if (!is_null($reply_id = $this->issueReplyEntity->getId())) {
            $issue_replay_do->response_id = $reply_id;
        }

        $issue_replay_do->issue_id = $this->issueReplyEntity->getIssueId();

        if ($this->issueReplyEntity->getContent()->isImage()) {
            $issue_replay_do->img = (string) $this->issueReplyEntity->getContent();
        } else {
            $issue_replay_do->content = (string) $this->issueReplyEntity->getContent();
        }

        $issue_replay_do->from_type = IssueReplyEloquent::FROM_USER;

        if (isset($_SESSION['login_user']['uId']) && $this->issueReplyEntity->getAuthor()->getId() == (int)$_SESSION['login_user']['uId']) {
            $issue_replay_do->from_name = $_SESSION['login_user']['uName'];
        } else {
            $issue_replay_do->from_name->from_name = UserEloquent::query()
                ->where(UserEloquent::ID_FIELD, $this->issueReplyEntity->getId())
                ->value(UserEloquent::USER_NAME_FIELD);
        }

        return $issue_replay_do;
    }
}