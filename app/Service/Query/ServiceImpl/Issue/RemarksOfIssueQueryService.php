<?php
namespace App\Service\Query\ServiceImpl\Issue;

use App\Service\AbstractQueryApplicationService;
use App\Service\Query\DTO\Issue\RemarksOfIssueQuery\InputDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\DataObject\Eloquent\IssueNote;

class RemarksOfIssueQueryService extends  AbstractQueryApplicationService
{
    protected function handle(InputDTO $input): QueryApplicationServeResult
    {
        $query = IssueNote::query();
        $query->where(IssueNote::ISSUE_ID_FIELD, '=', $input->getIssueId());

        $list = $query->get();

        return QueryApplicationServeResult::make(null, ['list' => $list]);
    }
}