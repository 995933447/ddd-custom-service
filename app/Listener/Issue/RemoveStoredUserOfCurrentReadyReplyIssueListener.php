<?php
namespace App\Listener\Issue;

use App\Event\Issue\UserLeaveIssueReplyRoomEvent;
use Framework\AbstractInterface\Event\AbstractEvent;
use Framework\AbstractInterface\Event\AbstractListener;
use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;
use InvalidArgumentException;

class RemoveStoredUserOfCurrentReadyReplyIssueListener extends AbstractListener
{
    protected function handle(AbstractEvent $event)
    {
        $user_id = null;
        $issue_id = null;

        // Maybe other event will trigger listener in feature.
        if ($event instanceof UserLeaveIssueReplyRoomEvent) {
            $user_id = $event->getUserId();
            $issue_id = $event->getIssueId();
        } else {
            throw new InvalidArgumentException();
        }

        ($redis = RedisFactory::get())->connect()->hDel(RedisKeyEnum::issueIdBoundReplierUserIdMapTimeHash($issue_id), $user_id);
        $redis->connect()->hDel(RedisKeyEnum::issueIdBoundReplierUserIdMapFdHash($issue_id), $user_id);
    }
}