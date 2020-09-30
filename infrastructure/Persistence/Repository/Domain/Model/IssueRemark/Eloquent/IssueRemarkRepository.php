<?php
namespace Infrastructure\Persistence\Repository\Domain\Model\IssueRemark\Eloquent;

use Domain\Model\IssueRemark\IssueRemark;
use Domain\Model\IssueRemark\IssueRemarkRepository as IssueRemarkRepositoryInterface;
use Infrastructure\Persistence\Converter\Domain\Model\IssueRemark\Eloquent\IssueRemarkEntityToIssueNoteDOConverter;
use Infrastructure\Persistence\DataObject\Eloquent\IssueNote as IssueNoteOrm;

class IssueRemarkRepository implements IssueRemarkRepositoryInterface
{
    public function save(IssueRemark $issue_remark): void
    {
        $issue_note_orm = (new IssueRemarkEntityToIssueNoteDOConverter($issue_remark))->toDataObject();
        if (is_null($remark_id = $issue_remark->getId())) {
            $issue_note_orm->save();
        } else {
            $issue_remark->where(IssueNoteOrm::ID_FIELD, $remark_id)->update($issue_note_orm->toArray());
        }
    }
}