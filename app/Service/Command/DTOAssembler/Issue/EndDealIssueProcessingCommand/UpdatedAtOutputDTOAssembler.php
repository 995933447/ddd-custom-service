<?php
namespace App\Service\Command\DTOAssembler\Issue\EndDealIssueProcessingCommand;

use App\Service\AbstractDTO;
use App\Service\Command\DTO\Issue\EndDealIssueProcessingCommand\UpdatedAtOutputDTO;

class UpdatedAtOutputDTOAssembler extends AbstractCmdExeOutputDTOAssembler
{
    public function assemble(): AbstractDTO
    {
        return new UpdatedAtOutputDTO(['update_time' => $this->issue->getUpdatedAt()->format('Y-m-d H:i:s')]);
    }
}