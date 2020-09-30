<?php
namespace Infrastructure\Persistence\Database\Redis;

final class RedisKeyEnum
{
    public static function userIdBoundFdMapConnectTimeAtsHash(int $user_id): string
    {
        return "user_id:{$user_id}_bound_fds_hash";
    }

    public static function fdBoundUserIdHash(): string
    {
        return "fd_bound_user_id_hash";
    }

    public static function queriedIssueIdBoundUserIdsHash(int $issue_id): string
    {
        return "queried_issue_id:{$issue_id}_bound_user_id_map_fd_hash";
    }

    public static function issueIdBoundReplierUserIdMapFdHash(int $issue_id): string
    {
        return "issue_id:{$issue_id}_bound_replier_user_id_map_fd_hash";
    }

    public static function issueIdBoundReplierUserIdMapTimeHash(int $issue_id): string
    {
        return "issue_id:{$issue_id}_bound_replier_user_id_map_reply_time_hash";
    }

    public static function userBoundReplyIssuesList(int $user_id): string
    {
        return "user_id:{$user_id}_reply_issues_list";
    }
}