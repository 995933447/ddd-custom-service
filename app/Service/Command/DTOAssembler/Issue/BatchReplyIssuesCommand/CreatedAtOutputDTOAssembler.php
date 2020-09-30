<?php
namespace App\Service\Command\DTOAssembler\Issue\BatchReplyIssuesCommand;

use App\Service\Command\DTO\Issue\BatchReplyIssuesCommand\CreatedAtOutputDTO;

class CreatedAtOutputDTOAssembler extends AbstractCmdExeOutputDTOAssembler
{
    public function assemble(): CreatedAtOutputDTO
    {
        return new CreatedAtOutputDTO(['addtime' => current($this->replies)->getCreatedAt()->format('Y-m-d H:i:s')]);
    }
}