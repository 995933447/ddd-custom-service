<?php
namespace App\Service\Command\DTO\Issue\EndDealIssueProcessingCommand;

use App\Service\AbstractDTO;

class UpdatedAtOutputDTO extends AbstractDTO
{
    protected $updateTime;

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $update_at
     */
    public function setUpdateTime(string $update_time): void
    {
        $this->updateTime = $update_time;
    }
}