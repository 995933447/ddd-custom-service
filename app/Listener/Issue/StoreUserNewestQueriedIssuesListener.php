<?php
namespace App\Listener\Issue;

use App\Event\Issue\UserQueryIssuesEvent;
use Infrastructure\Cache\CacheKeyEnum;
use Infrastructure\Cache\CacheRepositoryFactory;
use Framework\AbstractInterface\Event\AbstractListener;
use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;

class StoreUserNewestQueriedIssuesListener extends AbstractListener
{
    protected function handle(UserQueryIssuesEvent $event)
    {
        ($cache = CacheRepositoryFactory::make(CacheRepositoryFactory::REDIS_DRIVER))
            ->put(CacheKeyEnum::userNewestQueriedIssues($event->getUserId()), $event->getIssues());

        $redis = RedisFactory::get();
        foreach ($event->getIssues() as $issue) {
            $redis->connect()->hSet(
                RedisKeyEnum::queriedIssueIdBoundUserIdsHash((int) $issue['issue_id']),
                $event->getUserId(),
                $event->getUserId()
            );
        }
    }
}