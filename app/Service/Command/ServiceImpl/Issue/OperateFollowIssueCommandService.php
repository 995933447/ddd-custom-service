<?php
namespace App\Service\Command\ServiceImpl\Issue;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTOAssembler;
use App\Service\ApplicationServeException;
use App\Service\Command\DTO\Issue\OperateFollowIssueCommand\InputDTO;
use Domain\Model\Issue\IssueRepository;

class OperateFollowIssueCommandService extends AbstractCommandApplicationService
{
    protected $issueRepository;

    public function __construct(IssueRepository $issue_repository)
    {
        $this->issueRepository = $issue_repository;
    }

    protected function handle(InputDTO $input, AbstractDTOAssembler $output_assembler): void
    {
        if (is_null($issue = $this->issueRepository->findById($input->getIssueId()))) {
            throw new ApplicationServeException('工单不存在.');
        }

        if ($input->getIsFollowed()) {
            $issue->follow();
        } else {
            $issue->unfollow();
        }

        $this->issueRepository->save($issue);

        $output_assembler->compress($issue);
    }
}