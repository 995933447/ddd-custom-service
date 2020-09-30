<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\IssueReply\Eloquent;

use Infrastructure\Persistence\Converter\DomainEntityToDOConverterInterface;
use Infrastructure\Persistence\DataObject\Eloquent\Issue;
use Infrastructure\Persistence\DataObject\Eloquent\Issue as IssueEloquent;
use Domain\Model\IssueReply\IssueReply as IssueReplyEntity;
use Infrastructure\Persistence\DataObject\Eloquent\User as UserEloquent;

class IssueReplyEntityToIssueDOConverter implements DomainEntityToDOConverterInterface
{
    protected $issueReplyEntity;

    public function __construct(IssueReplyEntity $issue_reply_entity)
    {
        $this->issueReplyEntity = $issue_reply_entity;
    }

    public function toDataObject(): IssueEloquent
    {
        $issue_do = (new IssueEloquent)->where(IssueEloquent::ISSUE_ID_FIELD, $this->issueReplyEntity->getIssueId())->first();

        if (is_null($issue_do)) {
            throw new \RuntimeException("Issue not exist.");
        }

        $issue_do->service_response = IssueEloquent::ISSUE_HAS_RESPONSE;

        if (isset($_SESSION['login_user']['uId']) && $this->issueReplyEntity->getAuthor()->getId() == (int)$_SESSION['login_user']['uId']) {
            $issue_do->last_response = $_SESSION['login_user']['uName'];
        } else {
            $issue_do->last_response = UserEloquent::query()
                ->where(UserEloquent::ID_FIELD, $this->issueReplyEntity->getId())
                ->value(UserEloquent::USER_NAME_FIELD);
        }

        $issue_do->updatetime = date('Y-m-d H:i:s');

        return $issue_do;
    }
}