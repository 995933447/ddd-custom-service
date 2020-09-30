<?php
namespace App\Service\Query\ServiceImpl\Game;

use App\Service\AbstractQueryApplicationService;
use App\Service\NullDTO;
use App\Service\QueryApplicationServeResult;
use Infrastructure\Persistence\DataObject\Eloquent\Game;

class GameListQueryService extends AbstractQueryApplicationService
{
    protected function handle(NullDTO $_): QueryApplicationServeResult
    {
        $query = Game::query();
        $query->where(Game::IS_SHOW_FIELD, '=', Game::SHOW_STATUS);
        $query->orderBy(Game::ID_FIELD, 'desc');

        $list = [];
        foreach ($query->get() as $item) {
            if (!isset($list[$item[Game::APP_LANGUAGE_FIELD]]) || !$list[$item[Game::APP_LANGUAGE_FIELD]]) {
                $list[$item[Game::APP_LANGUAGE_FIELD]]['label'] = $item[Game::APP_LANGUAGE_FIELD];
                $list[$item[Game::APP_LANGUAGE_FIELD]]['value'] = $item[Game::APP_LANGUAGE_FIELD];
            }
            $list[$item['app_lang']]['children'][] = [
                'value' => $item[Game::ID_FIELD],
                'label' => "【{$item[Game::ID_FIELD]}】{$item[Game::NAME_FIELD]} - {$item[Game::INNER_NAME_FIELD]}",
            ];
        }

        return QueryApplicationServeResult::make(null, ['list' => array_values($list)]);
    }
}