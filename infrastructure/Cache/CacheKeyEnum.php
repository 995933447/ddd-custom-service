<?php
namespace Infrastructure\Cache;

final class CacheKeyEnum
{
    public static function bindServerInfoToUser(int $user_id)
    {
        return "user_id:{$user_id}_bound_server_info_hash";
    }

    public static function userNewestQueriedIssues(int $user_id)
    {
        return "user_id:{$user_id}_newest_queried_issues";
    }

    public static function queriedIssueBoundUser(int $issue_id)
    {
        return "issue_id:{$issue_id}_bound_user";
    }
}