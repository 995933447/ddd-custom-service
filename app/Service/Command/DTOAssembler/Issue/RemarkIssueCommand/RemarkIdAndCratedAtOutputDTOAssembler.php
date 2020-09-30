<?php
namespace App\Service\Command\DTOAssembler\Issue\RemarkIssueCommand;

use App\Service\AbstractDTOAssembler;
use App\Service\Command\DTO\Issue\RemarkIssueCommand\RemarkIdAndCreatedAtOutputDTO;

class RemarkIdAndCratedAtOutputDTOAssembler extends AbstractCmdExeOutputDTOAssembler
{
    public function assemble(): RemarkIdAndCreatedAtOutputDTO
    {
        return new RemarkIdAndCreatedAtOutputDTO(
            [
                'addtime' => $this->issueRemark->getCreatedAt()->format('Y-m-d H:i:s'),
                'last_node_id' => $this->issueRemark->getId()
            ]
        );
    }
}