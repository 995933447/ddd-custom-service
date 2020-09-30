<?php
namespace App\Service\Command\DTOAssembler\Issue\ReplyIssueCommand;

use App\Service\Command\DTO\Issue\ReplyIssueImageMessageCommand\ImageUrlAndUpdatedAtOutputDTO;

class ImageUrlAndCreatedAtOutputDTOAssembler extends AbstractCmdExeOutputDTOAssembler
{
    public function assemble(): ImageUrlAndUpdatedAtOutputDTO
    {
        return new ImageUrlAndUpdatedAtOutputDTO(
            ['image_url' => $this->issueReply->getContent(), 'addtime' => $this->issueReply->getCreatedAt()->format('Y-m-d H:i:s')]
        );
    }
}