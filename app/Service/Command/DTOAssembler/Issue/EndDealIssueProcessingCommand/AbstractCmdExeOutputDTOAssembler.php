<?php
namespace App\Service\Command\DTOAssembler\Issue\EndDealIssueProcessingCommand;

use App\Service\AbstractDTOAssembler;
use Domain\Model\Issue\Issue;

abstract class AbstractCmdExeOutputDTOAssembler extends AbstractDTOAssembler
{
    /**
     * @var Issue
     */
    protected $issue;

    public function compress($data)
    {
        if (!$data instanceof Issue) {
            throw new \InvalidArgumentException();
        }

        $this->issue = $data;
    }
}