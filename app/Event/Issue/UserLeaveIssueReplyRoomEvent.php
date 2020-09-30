<?php
namespace App\Event\Issue;

use App\Event\AbstractApplicationEvent;
use App\Listener\Issue\BroadcastSomeoneDoneReplyIssueListener;
use App\Listener\Issue\RemoveStoredUserOfCurrentReadyReplyIssueListener;
use Swoole\Websocket\Server;

class UserLeaveIssueReplyRoomEvent extends AbstractApplicationEvent
{
    protected $userId;

    protected $issueId;

    protected $server;

    public function __construct(int $user_id, int $issue_id, Server $server)
    {
        $this->userId = $user_id;
        $this->issueId = $issue_id;
        $this->server = $server;
    }

    public function getSwooleServer()
    {
        return $this->server;
    }

    public function getIssueId(): int
    {
        return $this->issueId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getListeners(): array
    {
        return [
            RemoveStoredUserOfCurrentReadyReplyIssueListener::class,
            BroadcastSomeoneDoneReplyIssueListener::class
        ];
    }
}