<?php
namespace App\Listener\Issue;

use App\Event\Issue\UserLeaveIssueReplyRoomEvent;
use App\Websocket\IO\Push\JsonMessage;
use Framework\AbstractInterface\Event\AbstractListener;
use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;

class BroadcastSomeoneDoneReplyIssueListener extends AbstractListener
{
    protected function handle(UserLeaveIssueReplyRoomEvent $event)
    {
        $user_ids = ($redis = RedisFactory::get())
            ->connect()
            ->hGetAll(RedisKeyEnum::queriedIssueIdBoundUserIdsHash($event->getIssueId()));

        foreach ($user_ids as $user_id) {
            $fd_with_connect_times = $redis->connect()->hGetAll(RedisKeyEnum::userIdBoundFdMapConnectTimeAtsHash((int) $user_id));

            array_walk($fd_with_connect_times, function ($_, $fd) use ($event) {
                JsonMessage::toPushNormalJson($event->getSwooleServer(), $fd, [
                    'cmd' => 'updateWhoReplyIssues'
                ]);
            });
        }
    }
}