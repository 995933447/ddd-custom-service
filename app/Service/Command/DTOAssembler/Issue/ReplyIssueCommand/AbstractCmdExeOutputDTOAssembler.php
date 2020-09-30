<?php
namespace App\Service\Command\DTOAssembler\Issue\ReplyIssueCommand;

use App\Service\AbstractDTOAssembler;
use Domain\Model\IssueReplay\IssueReply;

abstract class AbstractCmdExeOutputDTOAssembler extends AbstractDTOAssembler
{
    /**
     * @var IssueReply
     */
    protected $issueReply;

    public function compress($data)
    {
        if ($data instanceof IssueReply) {
            throw new \InvalidArgumentException();
        }

        $this->issueReply = $data;
    }
}