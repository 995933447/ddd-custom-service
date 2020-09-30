<?php
namespace App\Service\Query\ServiceImpl\Issue;

use App\Service\AbstractQueryApplicationService;
use App\Service\Query\DTO\Issue\IssueListQuery\InputDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\DataObject\Eloquent\Issue;
use Infrastructure\Persistence\DataObject\Eloquent\Game;
use Infrastructure\Persistence\DataObject\Eloquent\IssueType;

class IssueListQueryService extends AbstractQueryApplicationService
{
    const QUERY_ALL_STATUS = 1;

    const QUERY_NOT_RESPONSE_STATUS = 2;

    const QUERY_WAIT_PLAYER_RESPONSE_STATUS = 3;

    const QUERY_WAIT_CUSTOMER_RESPONSE_STATUS = 4;

    const QUERY_FINISH_STATUS = 5;

    protected function handle(InputDTO $input): QueryApplicationServeResult
    {
        $issue_query = new Issue();
        $game_do = new Game();
        $issue_query_table = $issue_query->getTable();

        $issue_query = $issue_query->leftJoin(
            sprintf('%s.%s  AS game', $game_do->getConnection()->getDatabaseName(), $game_do->getTable()),
            $issue_query_table . '.' . Issue::GAME_ID_FIELD,
            '=',
            'game.' . Game::ID_FIELD
        );

        $issue_type_do = new IssueType();

        $issue_query = $issue_query->leftJoin(
            $issue_type_do->getConnection()->getDatabaseName() . '.' . $issue_type_do->getTable(),
            $issue_query_table . '.' . Issue::TYPE_ID_FIELD,
            '=',
            $issue_type_do->getTable() . '.' . IssueType::ID_FIELD);

        if ($game_id_list = $input->getGameIdList()) {
            $issue_query->whereIn($issue_query_table . '.' . Issue::GAME_ID_FIELD, $game_id_list);
        }
        if ($issue_type_id = $input->getIssueId()) {
            $issue_query->where($issue_query_table . '.' . Issue::TYPE_ID_FIELD, '=', $issue_type_id);
        }
        if ($issue_id = $input->getIssueId()) {
            $issue_query->where($issue_query_table . '.' . Issue::ISSUE_ID_FIELD, '=', $issue_id);
        }
        if ($user_name = $input->getUsername()) {
            $issue_query->where($issue_query_table . '.' . Issue::USER_NAME_FIELD, 'like', '%' . $user_name . '%');
        }
        if ($role_name = $input->getRoleName()) {
            $issue_query->where($issue_query_table . '.' . Issue::GAME_ROLE_NAME_FIELD, 'like', '%' . $role_name . '%');
        }
        if ($service_name = $input->getServiceName()) {
            $issue_query->where($issue_query_table . '.' . Issue::LAST_REPLIER_FIELD, 'like', '%' . $service_name . '%');
        }
        if ($start_date = $input->getStartDate()) {
            $issue_query->where($issue_query_table . '.' . Issue::UPDATE_TIME_FIELD, '>=', $start_date);
        }
        if ($end_date = $input->getEndDate()) {
            $issue_query->where($issue_query_table . '.' . Issue::UPDATE_TIME_FIELD, '<=', $end_date);
        }
        if ($is_star = $input->getIsFollowed()) {
            $issue_query->where($issue_query_table . '.' . Issue::IS_FOLLOW_FIELD, '=', $is_star);
        }

        switch ($issue_status = $input->getIssueStatus()) {
            case static::QUERY_ALL_STATUS:
                //状态选择全部的时候，什么条件都不加
                break;
            case static::QUERY_NOT_RESPONSE_STATUS:
                $issue_query->where($issue_query_table . '.' . Issue::HAS_REPLY_RESPONSE_FIELD, '=', Issue::ISSUE_NOT_RESPONSE);
                $issue_query->where($issue_query_table . '.' . Issue::WORK_DEAL_STATUS_FIELD, '=', Issue::WORK_NOT_DEAL_STATUS);
                break;
            case static::QUERY_WAIT_PLAYER_RESPONSE_STATUS:
                $issue_query->whereColumn($issue_query_table . '.' . Issue::USER_NAME_FIELD, '!=', $issue_query_table . '.last_response');
                $issue_query->where($issue_query_table . '.' . Issue::HAS_REPLY_RESPONSE_FIELD, '=', Issue::ISSUE_HAS_RESPONSE);
                $issue_query->where($issue_query_table . '.' . Issue::WORK_DEAL_STATUS_FIELD, '=', Issue::WORK_NOT_DEAL_STATUS);
                break;
            case static::QUERY_WAIT_CUSTOMER_RESPONSE_STATUS:
                $issue_query->whereColumn($issue_query_table . '.' . Issue::USER_NAME_FIELD, '=', $issue_query_table . '.last_response');
                $issue_query->where($issue_query_table . '.' . Issue::HAS_REPLY_RESPONSE_FIELD, '=', Issue::ISSUE_HAS_RESPONSE);
                $issue_query->where($issue_query_table . '.' . Issue::WORK_DEAL_STATUS_FIELD, '=', Issue::WORK_NOT_DEAL_STATUS);
                break;
            case static::QUERY_FINISH_STATUS:
                $issue_query->where($issue_query_table . '.' . Issue::WORK_DEAL_STATUS_FIELD, '=', Issue::WORK_HAS_DEAL_STATUS);
                break;
            default:
                !$issue_id && $issue_query->where($issue_query_table . '.' . Issue::WORK_DEAL_STATUS_FIELD, '=', Issue::WORK_NOT_DEAL_STATUS);
        }

        $total_count = $issue_query->count();

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
            $issue_query_table . '.' . Issue::IS_FOLLOW_FIELD,
            ($issue_type_do = new IssueType())->getConnection()->getDatabaseName() . '.' . $issue_type_do->getTable() . '.'
            . IssueType::NAME_FIELD . ' AS issue_type_name',
            'game.' . Game::NAME_FIELD . ' AS game_name',
            'game.' . Game::INNER_NAME_FIELD . ' AS game_in_name'
        ]);
        $issue_query->orderBy($issue_query_table . '.' . Issue::UPDATE_TIME_FIELD, 'DESC');
        $issue_query->orderBy($issue_query_table . '.' . Issue::ISSUE_ID_FIELD, 'DESC');
        $issue_list = $issue_query->forPage($input->getPage(), $input->getPerGageLimit())->get();

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

            if ($item['work_deal'] === Issue::WORK_HAS_DEAL_STATUS) {
                $issue_status = '已处理';
            }

            $item['issue_status'] = $issue_status;
            $list[] = $item;
        }

        return QueryApplicationServeResult::make(null, ['list' => $list, 'total' => $total_count]);
    }
}