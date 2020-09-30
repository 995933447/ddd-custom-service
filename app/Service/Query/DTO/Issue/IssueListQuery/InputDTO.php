<?php
namespace App\Service\Query\DTO\Issue\IssueListQuery;

use App\Service\AbstractDTO;

class InputDTO extends AbstractDTO
{
    /**
     * @var array
     */
    protected $gameIdList;

    /**
     * @var int
     */
    protected $issueTypeId;

    /**
     * @var int
     */
    protected $issueStatus;

    /**
     * @var int
     */
    protected $issueId;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $roleName;

    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var string
     */
    protected $startDate;

    /**
     * @var string
     */
    protected $endDate;

    /**
     * @var bool
     */
    protected $isFollowed;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $perGageLimit;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPerGageLimit(): int
    {
        return $this->perGageLimit;
    }

    /**
     * @param int $per_gage_limit
     */
    public function setPerPageLimit(int $per_gage_limit): void
    {
        $this->perGageLimit = $per_gage_limit;
    }

    /**
     * @return array
     */
    public function getGameIdList(): array
    {
        return $this->gameIdList;
    }

    /**
     * @param array $game_id_list
     */
    public function setGameIdList(array $game_id_list): void
    {
        $this->gameIdList = $game_id_list;
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    /**
     * @param string $roleName
     */
    public function setRoleName(string $role_name): void
    {
        $this->roleName = $role_name;
    }

    /**
     * @return int
     */
    public function getIssueTypeId(): int
    {
        return $this->issueTypeId;
    }

    /**
     * @param int $issueTypeId
     */
    public function setIssueTypeId(int $issue_type_id): void
    {
        $this->issueTypeId = $issue_type_id;
    }

    /**
     * @return int
     */
    public function getIssueStatus(): int
    {
        return $this->issueStatus;
    }

    /**
     * @param int $issue_status
     */
    public function setIssueStatus(int $issue_status): void
    {
        $this->issueStatus = $issue_status;
    }

    /**
     * @return int
     */
    public function getIssueId(): int
    {
        return $this->issueId;
    }

    /**
     * @param int $issue_id
     */
    public function setIssueId(int $issue_id): void
    {
        $this->issueId = $issue_id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->userName;
    }

    /**
     * @param string $issue_name
     */
    public function setUserName(string $user_name): void
    {
        $this->userName = $user_name;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $service_name
     */
    public function setServiceName(string $service_name): void
    {
        $this->serviceName = $service_name;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @param string $start_date
     */
    public function setStartDate(string $start_date): void
    {
        $this->startDate = $start_date;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->endDate;
    }

    /**
     * @param string $end_date
     */
    public function setEndDate(string $end_date): void
    {
        $this->endDate = $end_date;
    }

    /**
     * @return bool
     */
    public function getIsFollowed(): bool
    {
        return $this->isFollowed;
    }

    /**
     * @param bool $is_follow
     */
    public function setIsFollowed(bool $is_followed): void
    {
        $this->isFollowed = $is_followed;
    }
}