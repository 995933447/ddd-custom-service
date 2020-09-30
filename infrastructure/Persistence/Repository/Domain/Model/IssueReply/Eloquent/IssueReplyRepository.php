<?php
namespace Infrastructure\Persistence\Repository\Domain\Model\IssueReply\Eloquent;

use Domain\Model\IssueReply\IssueReply;
use Domain\Model\IssueReply\IssueReplyRepository as IssueReplyRepositoryInterface;
use Infrastructure\Persistence\Converter\Domain\Model\IssueReply\Eloquent\IssueReplyEntityToDOConverter;
use Infrastructure\Persistence\Converter\Domain\Model\IssueReply\Eloquent\IssueReplyEntityToIssueDOConverter;
use Infrastructure\Persistence\DataObject\Eloquent\IssueReply as IssueReplyOrm;

class IssueReplyRepository implements IssueReplyRepositoryInterface
{
    public function save(IssueReply $issue_reply): void
    {
        $issue_orm = (new IssueReplyEntityToIssueDOConverter($issue_reply))->toDataObject();
        $issue_orm->save();

        $issue_reply_orm = (new IssueReplyEntityToDOConverter($issue_reply))->toDataObject();
        if (is_null($issue_reply->getId())) {
            $issue_reply_orm->save();
        } else {
            $issue_reply_orm->where(IssueReplyOrm::ID_FIELD, $issue_reply->getId())->update($issue_reply_orm->toArray());
        }

        $issue_reply->setId($issue_reply_orm->response_id);
    }
}