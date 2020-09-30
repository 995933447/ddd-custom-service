<?php
namespace App\Service\Query\ServiceImpl\Issue;

use App\Service\AbstractQueryApplicationService;
use App\Service\Query\DTO\Issue\IssueOfUserQuery\InputDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\DataObject\Eloquent\Issue;
use Infrastructure\Persistence\DataObject\Eloquent\Game;
use Infrastructure\Persistence\DataObject\Eloquent\IssueType;

class IssuesOfUserQueryService extends AbstractQueryApplicationService
{
    protected function handle(InputDTO $input): QueryApplicationServeResult
    {
        $issue_id = $input->getExcludeIssueId();
        $user_name = $input->getUserName();

        $game_do = new Game();
        $issue_type_do = new IssueType();
        
        $issue_query = new Issue();
        $issue_query_table = $issue_query->getTable();

        $issue_query = $issue_query->leftJoin(
            $game_do->getConnection()->getDatabaseName() . '.' . $game_do->getTable() . ' AS game',
            $issue_query_table . '.' . Issue::GAME_ID_FIELD,
            '=',
            'game.' . Game::ID_FIELD
        );
        $issue_query = $issue_query->leftJoin(
            $issue_type_do->getConnection()->getDatabaseName() . '.' . $issue_type_do->getTable(),
            $issue_query_table . '.' . Issue::TYPE_ID_FIELD,
            '=',
            $issue_type_do->getTable() . '.' . Issue::TYPE_ID_FIELD
        );

        $issue_query->where($issue_query_table . '.' . Issue::ISSUE_ID_FIELD, '!=', $issue_id);
        $issue_query->where($issue_query_table . '.' . Issue::USER_NAME_FIELD, '=', $user_name);

        $issue_query->select([
            $issue_query_table . '.' . Issue::ISSUE_ID_FIELD,
            $issue_query_table . '.' . Issue::USER_NAME_FIELD,
            $issue_query_table . '.' . Issue::GAME_ROLE_NAME_FIELD,
            $issue_query_table . '.' . Issue::TITLE_FIELD,
            $issue_query_table . '.' . Issue::ADD_TIME_FIELD,
            $issue_query_table . '.' . Issue::UPDATE_TIME_FIELD,
            $issue_query_table . '.' . Issue::LAST_REPLIER_FIELD,
            $issue_query_table . '.' . Issue::HAS_REPLY_RESPONSE_FIELD,
            $issue_query_table . '.' . Issue::WORK_DEAL_STATUS_FIELD,
            $issue_type_do->getTable() . '.' . IssueType::NAME_FIELD . ' AS issue_type_name',
            'game.' . Game::NAME_FIELD . ' AS game_name',
            'game.' . Game::INNER_NAME_FIELD . ' AS game_in_name'
        ]);
        $issue_query->orderBy($issue_query_table . '.' . Issue::ISSUE_ID_FIELD, 'DESC');
        $issue_list = $issue_query->limit(10)->get();

        $list = [];
        foreach ($issue_list as $key => $item) {
            $issue_status = '';

            if ($item[Issue::HAS_REPLY_RESPONSE_FIELD] == Issue::ISSUE_NOT_RESPONSE) {
                $issue_status = '未回复';
            } else {
                if ($item[Issue::USER_NAME_FIELD] == $item[Issue::LAST_REPLIER_FIELD]) {
                    $issue_status = '等待客服回复';
                } else {
                    $issue_status = '等待玩家回复';
                }
            }

            if ($item[Issue::WORK_DEAL_STATUS_FIELD] == Issue::WORK_HAS_DEAL_STATUS) {
                $issue_status = '已处理';
            }

            $item['issue_status'] = $issue_status;
            $list[] = $item;
        }

        return QueryApplicationServeResult::make(null, ['list' => $list]);
    }
}