<?php
namespace App\Service\Command\DTO\Issue\BatchReplyIssuesCommand;

use App\Service\AbstractDTO;

class CreatedAtOutputDTO extends AbstractDTO
{
    /**
     * @var string
     */
    protected $addtime;

    /**
     * @return string
     */
    public function getAddtime(): string
    {
        return $this->addtime;
    }

    /**
     * @param string $addtime
     */
    public function setAddtime(string $addtime): void
    {
        $this->addtime = $addtime;
    }
}