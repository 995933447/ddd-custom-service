<?php
namespace App\Service\Command\DTOAssembler\Issue\ReplyIssueCommand;

use App\Service\Command\DTO\Issue\ReplyIssueCommand\ReplayIdAndCreateAtOutputDTO;

class ReplayIdAndCreatedAtOutputDTOAssembler extends AbstractCmdExeOutputDTOAssembler
{
    public function assemble(): ReplayIdAndCreateAtOutputDTO
    {
        return new ReplayIdAndCreateAtOutputDTO(
            [
                'last_response_id' => $this->issueReply->getId(),
                'addtime' => $this->issueReply->getCreatedAt()->format('Y-m-d H:i:s')
            ]
        );
    }
}