<?php
namespace App\Event\Issue;

use App\Event\AbstractApplicationEvent;
use App\Listener\Issue\BroadcastIssueReadyReplyListener;
use App\Listener\Issue\StoreUserCurrentReadyReplyIssueListener;
use Swoole\Websocket\Server;

class UserReadyReplyIssueEvent extends AbstractApplicationEvent
{
    protected $issueId;

    protected $userId;

    protected $server;

    protected $replyTime;

    protected $fd;

    public function __construct(int $issue_id, int $user_id, \DateTimeImmutable $reply_time, Server $server, int $fd)
    {
        $this->issueId = $issue_id;
        $this->userId = $user_id;
        $this->server = $server;
        $this->replyTime = $reply_time;
        $this->fd = $fd;
    }

    public function getReplyTime(): \DateTimeImmutable
    {
        return $this->replyTime;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getIssueId(): int
    {
        return $this->issueId;
    }

    public function getSwooleServer(): Server
    {
        return $this->server;
    }

    public function getFd(): int
    {
        return $this->fd;
    }

    public function getListeners(): array
    {
        return [
            BroadcastIssueReadyReplyListener::class,
            StoreUserCurrentReadyReplyIssueListener::class
        ];
    }
}