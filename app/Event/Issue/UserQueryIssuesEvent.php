<?php
namespace App\Event\Issue;

use App\Event\AbstractApplicationEvent;
use App\Listener\Issue\StoreUserNewestQueriedIssuesListener;

class UserQueryIssuesEvent extends AbstractApplicationEvent
{
    protected $issues;

    protected $userId;

    public function __construct(int $user_id, array $issues)
    {
        $this->userId = $user_id;
        $this->issues = $issues;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getIssues(): array
    {
        return $this->issues;
    }

    public function getListeners(): array
    {
        return [
            StoreUserNewestQueriedIssuesListener::class,
        ];
    }
}