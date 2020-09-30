<?php
namespace App\Service\Command\DTOAssembler\Issue\BatchReplyIssuesCommand;

use App\Service\AbstractDTOAssembler;
use Domain\Model\Issue\Issue;
use Domain\Model\IssueReply\IssueReply;

abstract class AbstractCmdExeOutputDTOAssembler extends AbstractDTOAssembler
{
    /**
     * @var array
     */
    protected $issues;

    /**
     * @var array
     */
    protected $replies;

    public function compress($data)
    {
        if (
            !is_array($data) ||
            !isset($data['issues']) ||
            empty($data['issues']) ||
            !isset($data['replies']) ||
            !is_array($data['replies']) ||
            empty($data['replies'])
        ) {
            throw new \InvalidArgumentException();
        }

        foreach ($data['replies'] as $reply) {
            if (!$reply instanceof IssueReply) {
                throw new \InvalidArgumentException();
            }
        }

        foreach ($data['issues'] as $issue) {
            if (!$issue instanceof Issue) {
                throw new \InvalidArgumentException();
            }
        }

        $this->issues = $data['issues'];
        $this->replies = $data['replies'];
    }
}