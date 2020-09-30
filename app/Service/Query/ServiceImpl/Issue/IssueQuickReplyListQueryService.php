<?php
namespace App\Service\Query\ServiceImpl\Issue;


use App\Service\AbstractQueryApplicationService;
use App\Service\Query\DTO\Issue\IssueQuickReplyListQuery\InputDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\DataObject\Eloquent\QuickReply;

class IssueQuickReplyListQueryService extends AbstractQueryApplicationService
{
    protected function handle(InputDTO $input): QueryApplicationServeResult
    {
        $query = QuickReply::query();
        $query->where(QuickReply::GAME_ID_FIELD, '=', $input->getGameId());
        $query->orderBy(QuickReply::SORT_FIELD, 'asc');
        $data_list = $query->get([
            QuickReply::ID_FIELD,
            QuickReply::CONTENT_FIELD
        ]);

        $list = [];
        foreach ($data_list as $item) {
            $list[] = [
                'id' => $item[QuickReply::ID_FIELD],
                'content' => stripslashes($item[QuickReply::CONTENT_FIELD])
            ];
        }

        return QueryApplicationServeResult::make(null, ['list' => $list]);
    }
}
