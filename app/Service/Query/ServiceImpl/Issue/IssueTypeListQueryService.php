<?php
namespace App\Service\Query\ServiceImpl\Issue;

use App\Service\AbstractQueryApplicationService;
use App\Service\NullDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\DataObject\Eloquent\IssueType;

class IssueTypeListQueryService extends AbstractQueryApplicationService
{
    protected function handle(NullDTO $_): QueryApplicationServeResult
    {
        $query = IssueType::query();

        $list = $query->get([
            IssueType::ID_FIELD,
            IssueType::NAME_FIELD . ' As type_name',
        ]);

        return QueryApplicationServeResult::make(null, ['list' => $list]);
    }
}