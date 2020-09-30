<?php
namespace App\Service\Command\DTOAssembler\Issue\RemarkIssueCommand;

use App\Service\AbstractDTOAssembler;
use Domain\Model\IssueRemark\IssueRemark;

abstract class AbstractCmdExeOutputDTOAssembler extends AbstractDTOAssembler
{
    /**
     * @var IssueRemark
     */
    protected $issueRemark;

    public function compress($data)
    {
        if (!$data instanceof IssueRemark) {
            throw new \InvalidArgumentException();
        }

        $this->issueRemark = $data;
    }
}