<?php
namespace App\Websocket\Controller;

use App\Event\Issue\UserLeaveIssueReplyRoomEvent;
use App\Event\Issue\UserReadyReplyIssueEvent;
use App\Websocket\IO\Push\JsonMessage;
use Framework\Event\EventDispatcher;
use Infrastructure\Persistence\Database\Redis\RedisFactory;
use Infrastructure\Persistence\Database\Redis\RedisKeyEnum;
use Swoole\Websocket\Server;

class IssueController
{
    public function readyReplyIssue(Server $server, $frame, array $data)
    {
        if (isset($data['issue_id'])) {
            $redis = RedisFactory::get();
            $user_id = $redis->connect()->hGet(RedisKeyEnum::fdBoundUserIdHash(), $frame->fd);

            EventDispatcher::dispatch(
                new UserReadyReplyIssueEvent(
                    (int) $data['issue_id'],
                    (int) $user_id,
                    new \DateTimeImmutable(),
                    $server,
                    $frame->fd
                )
            );
        }
    }

    public function fetchWhoCurrentReplyIssue(Server $server, $frame, array $data)
    {
        if (isset($data['issue_ids']) && is_array($data['issue_ids'])) {
            $redis = RedisFactory::get();

            foreach ($data['issue_ids'] as $issue_id) {
                $replier_id_with_times = $redis->connect()->hGetAll(
                    RedisKeyEnum::issueIdBoundReplierUserIdMapTimeHash((int) $issue_id)
                );

                if (!$replier_id_with_times) {
                    continue;
                }

                $replier_time_with_ids = array_flip($replier_id_with_times);
                krsort($replier_time_with_ids);

                foreach ($replier_time_with_ids as $time => $user_id) {
                    $replier_fd = $redis->connect()->hGet(RedisKeyEnum::issueIdBoundReplierUserIdMapFdHash($issue_id), $user_id);
                    EventDispatcher::dispatch(
                        new UserReadyReplyIssueEvent(
                            (int) $issue_id,
                            (int) $user_id,
                            new \DateTimeImmutable(date('Y-m-d H:i:s', (int)$time)),
                            $server,
                            $replier_fd
                        )
                    );
                }
            }
        }
    }

    public function broadIssueReply(Server $server, $frame, array $data)
    {
        if (
            isset($data['issue_id']) && isset($data['content']) && is_string($data['content'])
            && isset($data['time']) && is_string($data['time'])
            && isset($data['user_name']) && is_string($data['user_name'])
        ) {
            $redis = RedisFactory::get();

            $replier_id_with_replier_fds = $redis->connect()->hGetAll(
                RedisKeyEnum::issueIdBoundReplierUserIdMapFdHash((int) $data['issue_id'])
            );

            foreach ($replier_id_with_replier_fds as $replier_fd) {
                if ((int) $replier_fd === $frame->fd) {
                    continue;
                }

                JsonMessage::toPushNormalJson($server, $replier_fd, [
                    'cmd' => 'broadIssueReply',
                    'content' => $data['content'],
                    'time' => $data['time'],
                    'user_name' => $data['user_name']
                ]);
            }
        } else {
            JsonMessage::toPushErrorJson($server, $frame->fd, 'Request issue.broadIssueReply:Params error.');
        }
    }

    public function finishReplyIssue(Server $server, $frame, array $data)
    {
        if (isset($data['issue_id'])) {
            $user_id = RedisFactory::get()->connect()->hGet(RedisKeyEnum::fdBoundUserIdHash(), $frame->fd);
            EventDispatcher::dispatch(new UserLeaveIssueReplyRoomEvent((int) $user_id, (int) $data['issue_id'], $server));
        } else {
            JsonMessage::toPushErrorJson($server, $frame->fd, 'Request issue.finishReplyIssue:Params error.');
        }
    }
}
