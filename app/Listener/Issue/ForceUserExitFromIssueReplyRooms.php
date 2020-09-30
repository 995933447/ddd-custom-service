<?php
namespace App\Listener\Issue;

use App\Event\Issue\UserLeaveIssueReplyRoomEvent;
use App\Event\Net\Websocket\SessionClosingEvent;
use Framework\AbstractInterface\Event\AbstractEvent;
use Framework\AbstractInterface\Event\AbstractListener;
use Framework\Event\EventDispatcher;
use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;

class ForceUserExitFromIssueReplyRooms extends AbstractListener
{
    protected function handle(AbstractEvent $event)
    {
        if ($event instanceof SessionClosingEvent) {
            $user_id_with_reply_issue_id = $this->dispatchUserIdAndIssueIdFromSessionClosingEvent($event);

            if (empty($user_id_with_reply_issue_id)) {
                return;
            }

            list($user_id, $reply_issue_id) = $user_id_with_reply_issue_id;
        } else {
            throw new \InvalidArgumentException();
        }

        EventDispatcher::dispatch(new UserLeaveIssueReplyRoomEvent($user_id, $reply_issue_id, $event->getServer()));
    }

    protected function dispatchUserIdAndIssueIdFromSessionClosingEvent(SessionClosingEvent $event): array
    {
        $redis = RedisFactory::get();

        $user_id = $redis->connect()->hGet(RedisKeyEnum::fdBoundUserIdHash(), $event->getFd());

        if (!$user_id) {
            return [];
        }

        $user_reply_issue_ids = $redis->connect()->lRange(RedisKeyEnum::userBoundReplyIssuesList($user_id), 0, -1);

        $reply_issue_id = null;
        foreach ($user_reply_issue_ids as $issue_id) {
            $fd = $redis->connect()->hGet(RedisKeyEnum::issueIdBoundReplierUserIdMapFdHash($issue_id), $user_id);
            if ($fd == $event->getFd()) {
                $reply_issue_id = $issue_id;
                break;
            }
        }

        if (is_null($reply_issue_id)) {
            return [];
        }

        return [$user_id, $reply_issue_id];
    }
}