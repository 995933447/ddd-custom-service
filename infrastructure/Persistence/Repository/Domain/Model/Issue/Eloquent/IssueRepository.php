<?php
namespace Infrastructure\Persistence\Repository\Domain\Model\Issue\Eloquent;

use Domain\Model\Issue\Issue;
use Domain\Model\Issue\IssueRepository as IssueRepositoryInterface;
use Infrastructure\Persistence\Converter\Domain\Model\Issue\Eloquent\DirtyFromCompareIssueEntitySnapshotToDOConverter;
use Infrastructure\Persistence\Converter\Domain\Model\Issue\Eloquent\IssueDOToEntityConverter;
use Infrastructure\Persistence\Converter\Domain\Model\Issue\Eloquent\IssueEntityToDOConverter;
use Infrastructure\Persistence\DataObject\Eloquent\Issue as IssueOrm;

class IssueRepository implements IssueRepositoryInterface
{
    protected $issueSnapshot = [];

    public function findById(int $id): ?Issue
    {
       $issue_orm = IssueOrm::query()->where(IssueOrm::ISSUE_ID_FIELD, $id)->first();
       if (!$issue_orm) {
           return null;
       }

       $this->recordSnapshot($issue = (new IssueDOToEntityConverter($issue_orm))->toEntity());

       return $issue;
    }

    public function findAndLockById(int $id): ?Issue
    {
        $issue_orm = IssueOrm::query()->where(IssueOrm::ISSUE_ID_FIELD, $id)->lockForUpdate()->first();
        if (!$issue_orm) {
            return null;
        }

        $this->recordSnapshot($issue = (new IssueDOToEntityConverter($issue_orm))->toEntity());

        return $issue;
    }

    public function exitById(int $id): bool
    {
        return IssueOrm::query()->where(IssueOrm::ISSUE_ID_FIELD, $id)->count() > 0;
    }

    public function getByIds(array $ids): array
    {
        $issue_orms = IssueOrm::query()->whereIn(IssueOrm::ISSUE_ID_FIELD, $ids)->get();
        if (!$issue_orms) {
            return [];
        }

        $issues = [];
        foreach ($issue_orms as $issue_orm) {
            $this->recordSnapshot($issue = (new IssueDOToEntityConverter($issue_orm))->toEntity());
            $issues[] = $issue;
        }

        return $issues;
    }

    protected function recordSnapshot(Issue $issue)
    {
        $this->issueSnapshot[$issue->getId()] = clone $issue;
    }

    public function save(Issue $issue): void
    {
        if (!is_null($issue->getId()) && !is_null($snapshot = $this->getSnapshot($issue->getId()))) {
            $issue_orm = (new DirtyFromCompareIssueEntitySnapshotToDOConverter($issue, $snapshot))->toDataObject();
        } else {
            $issue_orm = (new IssueEntityToDOConverter($issue))->toDataObject();
        }

        if (is_null($issue_id = $issue->getId())) {
            $issue_orm->save();
        } else {
            $issue_orm->where(IssueOrm::ISSUE_ID_FIELD, $issue_id)->update($issue_orm->toArray());
        }

        $issue->setId($issue_orm->issue_id);
    }

    public function getSnapshot(int $issue_id): ?Issue
    {
        return $this->issueSnapshot[$issue_id]?? null;
    }
}