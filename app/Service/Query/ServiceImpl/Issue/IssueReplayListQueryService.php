<?php
namespace App\Service\Query\ServiceImpl\Issue;

use App\Service\AbstractQueryApplicationService;
use App\Service\Query\DTO\Issue\IssueReplyListQuery\InputDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\Database\DB\DB;
use Infrastructure\Persistence\DataObject\Eloquent\IssueReply;

class IssueReplayListQueryService extends AbstractQueryApplicationService
{
    public function handle(InputDTO $input): QueryApplicationServeResult
    {
        $query = IssueReply::query()->where(IssueReply::ISSUE_ID_FIELD, '=', $input->getIssueId());
        $query->select([
            IssueReply::ID_FIELD,
            IssueReply::CONTENT_FIELD,
            IssueReply::FROM_TYPE_FIELD,
            IssueReply::FROM_NAME_FIELD,
            IssueReply::IMAGE_FIELD . ' AS image_url',
            IssueReply::ADD_TIME_FIELD
        ]);

        $list = $query->get();

        return QueryApplicationServeResult::make(null, ['list' => $list]);
    }
}