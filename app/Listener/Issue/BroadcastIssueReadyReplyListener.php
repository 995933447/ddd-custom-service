<?php
namespace App\Listener\Issue;

use App\Event\Issue\UserReadyReplyIssueEvent;
use App\Websocket\IO\Push\JsonMessage;
use Framework\AbstractInterface\Event\AbstractListener;
use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;
use Infrastructure\Persistence\DataObject\Eloquent\User;

class BroadcastIssueReadyReplyListener extends AbstractListener
{
    protected function handle(UserReadyReplyIssueEvent $event)
    {
        $redis = RedisFactory::get();

        $user_ids = $redis->connect()->hGetAll(RedisKeyEnum::queriedIssueIdBoundUserIdsHash($event->getIssueId()));

        $replier_name = User::query()->where(User::ID_FIELD, $event->getUserId())->value(User::USER_NAME_FIELD);

        foreach ($user_ids as $user_id) {
            $fd_with_connect_times = $redis->connect()->hGetAll(RedisKeyEnum::userIdBoundFdMapConnectTimeAtsHash((int) $user_id));

            array_walk($fd_with_connect_times, function ($_, $fd) use ($event, $replier_name) {
                JsonMessage::toPushNormalJson(
                    $event->getSwooleServer(),
                    (int) $fd,
                    [
                        'user_name' => $replier_name,
                        'issue_id' => $event->getIssueId(),
                        'cmd' => 'userReadyReply'
                    ]
                );
            });
        }
    }
}