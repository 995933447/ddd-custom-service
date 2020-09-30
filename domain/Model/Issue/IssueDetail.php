<?php
namespace Domain\Model\Issue;

use Domain\AbstractValueObject;

class IssueDetail extends AbstractValueObject
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $describe;

    /**
     * @var int
     */
    protected $serverId;

    /**
     * @var int
     */
    protected $gameRoleId;

    /**
     * @var string
     */
    protected $gameRoleName;

    /**
     * @var int
     */
    protected $gameId;

    /**
     * @var Machine
     */
    protected $machine;

    /**
     * @var string
     */

    /**
     * @var string
     */
    protected $gameVersion;

    /**
     * @var string
     */
    protected $sdkVersion;

    public function __construct(
        string $title,
        string $describe,
        int $server_id,
        string $game_role_name,
        int $game_role_id,
        int $game_id,
        Machine $machine,
        string $sdk_version,
        string $game_version
    )
    {
        $this->title = $title;
        $this->describe = $describe;
        $this->serverId = $server_id;
        $this->gameRoleName = $game_role_name;
        $this->gameRoleId = $game_role_id;
        $this->gameId = $game_id;
        $this->machine = $machine;
        $this->sdkVersion = $sdk_version;
        $this->gameVersion = $game_version;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescribe(): string
    {
        return $this->describe;
    }

    /**
     * @return int
     */
    public function getServerId(): int
    {
        return $this->serverId;
    }

    /**
     * @return int
     */
    public function getGameRoleId(): int
    {
        return $this->gameRoleId;
    }

    public function getGameRoleName(): string
    {
        return $this->gameRoleName;
    }

    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return Machine
     */
    public function getMachine(): Machine
    {
        return $this->machine;
    }

    /**
     * @return string
     */
    public function getSdkVersion(): string
    {
        return $this->sdkVersion;
    }

    /**
     * @return string
     */
    public function getGameVersion(): string
    {
        return $this->gameVersion;
    }

    public function equalsTo(IssueDetail $issue_detail): bool
    {
        return $this->getTitle() === $issue_detail->getTitle() &&
            $this->getSdkVersion() === $issue_detail->getSdkVersion() &&
            $this->getGameRoleId() === $issue_detail->getGameRoleId() &&
            $this->getServerId() === $issue_detail->getServerId() &&
            $this->getDescribe() === $issue_detail->getDescribe() &&
            $this->getMachine()->equalsTo($issue_detail->getMachine()) &&
            $this->getGameVersion() === $issue_detail->getGameVersion();
    }
}