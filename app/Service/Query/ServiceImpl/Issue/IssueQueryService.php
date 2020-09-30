<?php
namespace App\Service\Query\ServiceImpl\Issue;

use App\Service\AbstractQueryApplicationService;
use App\Service\Query\DTO\Issue\IssueQuery\InputDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\DataObject\Eloquent\Game;
use Infrastructure\Persistence\DataObject\Eloquent\Issue;
use Infrastructure\Persistence\DataObject\Eloquent\IssueType;

class IssueQueryService extends  AbstractQueryApplicationService
{
    protected function handle(InputDTO $input): QueryApplicationServeResult
    {
        $issue_type_do = new IssueType();
        $game_do = new Game();
        $issue_query = new Issue();
        $issue_query_table = $issue_query->getTable();

        $issue_query = $issue_query->leftJoin(
            sprintf('%s.%s AS game', $game_do->getConnection()->getName(), $game_do->getTable()),
            $issue_query_table . '.' . Issue::GAME_ID_FIELD,
            '=',
            'game.' . Game::ID_FIELD
        );
        $issue_query->leftJoin(
            $issue_type_do->getConnection()->getDatabaseName() . '.' . $issue_type_do->getTable(),
            $issue_query_table . '.' . Issue::TYPE_ID_FIELD,
            '=',
            $issue_type_do->getTable() . '.' . IssueType::ID_FIELD);

        $issue_query->where(Issue::ISSUE_ID_FIELD, '=', $input->getIssueId());
        $issue_query->select([
            $issue_query_table . '.' . Issue::ISSUE_ID_FIELD,
            $issue_query_table . '.' . Issue::USER_NAME_FIELD,
            $issue_query_table . '.' . Issue::GAME_ROLE_NAME_FIELD,
            $issue_query_table . '.' . Issue::GAME_ID_FIELD,
            $issue_query_table . '.' . Issue::SERVER_ID_FIELD,
            $issue_query_table . '.' . Issue::IP_FIELD,
            $issue_query_table . '.' . Issue::TITLE_FIELD,
            $issue_query_table . '.' . Issue::ADD_TIME_FIELD,
            $issue_query_table . '.' . Issue::UPDATE_TIME_FIELD,
            $issue_query_table . '.' . Issue::LAST_REPLIER_FIELD,
            $issue_query_table . '.' . Issue::HAS_REPLY_RESPONSE_FIELD,
            $issue_query_table . '.' . Issue::WORK_DEAL_STATUS_FIELD,
            $issue_query_table . '.' . Issue::MACHINE_MODEL_FIELD,
            $issue_query_table . '.' . Issue::MACHINE_OS_VERSION_FIELD,
            $issue_query_table . '.' . Issue::SDK_VERSION_FIELD,
            $issue_type_do->getTable() . '.' . IssueType::NAME_FIELD . ' AS issue_type_name',
            'game.' . Game::NAME_FIELD . ' AS game_name',
            'game.' . Game::INNER_NAME_FIELD . ' AS game_in_name'
        ]);

        return QueryApplicationServeResult::make(null, $issue_query->first());
    }
}