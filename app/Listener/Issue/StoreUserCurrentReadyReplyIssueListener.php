<?php
namespace App\Listener\Issue;

use App\Event\Issue\UserReadyReplyIssueEvent;
use Framework\AbstractInterface\Event\AbstractListener;
use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;

class StoreUserCurrentReadyReplyIssueListener extends AbstractListener
{
    protected function handle(UserReadyReplyIssueEvent $event)
    {
        $redis = RedisFactory::get();

        $redis->connect()->rPush(
            RedisKeyEnum::userBoundReplyIssuesList($event->getUserId()),
            $event->getIssueId()
        );

        $redis->connect()->hSet(
            RedisKeyEnum::issueIdBoundReplierUserIdMapTimeHash($event->getIssueId()),
            $event->getUserId(),
            $event->getReplyTime()->getTimestamp()
        );

        $redis->connect()->hSet(
            RedisKeyEnum::issueIdBoundReplierUserIdMapFdHash($event->getIssueId()),
            $event->getUserId(),
            $event->getFd()
        );
    }
}