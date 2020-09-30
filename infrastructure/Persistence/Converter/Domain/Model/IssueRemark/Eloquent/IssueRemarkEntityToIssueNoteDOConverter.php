<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\IssueRemark\Eloquent;

use Domain\Model\IssueRemark\IssueRemark as IssueRemarkEntity;
use Infrastructure\Persistence\Converter\DomainEntityToDOConverterInterface;
use Infrastructure\Persistence\DataObject\Eloquent\IssueNote as IssueNoteEloquent;
use Infrastructure\Persistence\DataObject\Eloquent\User as UserEloquent;

class IssueRemarkEntityToIssueNoteDOConverter implements DomainEntityToDOConverterInterface
{
    protected $issueRemarkEntity;

    public function __construct(IssueRemarkEntity $issue_remark_entity)
    {
        $this->issueRemarkEntity = $issue_remark_entity;
    }

    public function toDataObject(): IssueNoteEloquent
    {
        $issue_note_do = new IssueNoteEloquent();

        if (!is_null($note_id = $this->issueRemarkEntity->getId())) {
            $issue_note_do->note_id = $note_id;
            $issue_note_do->addtime = $this->issueRemarkEntity->getCreatedAt()->format('Y-m-d H:i:s');
        }

        $issue_note_do->issue_id = $this->issueRemarkEntity->getIssueId();

        $issue_note_do->from_name = $this->issueRemarkEntity->getUserId() === (int) $_SESSION['login_user']['uId']?
            $_SESSION['login_user']['uName']: UserEloquent::query()
                ->where(UserEloquent::ID_FIELD, $this->issueRemarkEntity->getUserId())
                ->value(UserEloquent::USER_NAME_FIELD);

        $issue_note_do->content = $this->issueRemarkEntity->getContent();

        return $issue_note_do;
    }
}