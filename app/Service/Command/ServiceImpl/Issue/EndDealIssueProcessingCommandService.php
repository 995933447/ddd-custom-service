<?php
namespace App\Service\Command\ServiceImpl\Issue;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTOAssembler;
use App\Service\ApplicationServeException;
use App\Service\Command\DTO\Issue\EndDealIssueProcessingCommand\InputDTO;
use Domain\Model\Issue\IssueProcessingProgress;
use Domain\Model\Issue\IssueRepository;

class EndDealIssueProcessingCommandService extends AbstractCommandApplicationService
{
    protected $issueRepository;

    public function __construct(IssueRepository $issue_repository)
    {
        $this->issueRepository = $issue_repository;
    }

    protected function handle(InputDTO $input, AbstractDTOAssembler $output_assembler)
    {
        if (is_null($issue = $this->issueRepository->findById($input->getIssueId()))) {
            throw new ApplicationServeException('工单不存在');
        }

        $issue->updateProcessProgress(IssueProcessingProgress::beFinish());

        $this->issueRepository->save($issue);

        $output_assembler->compress($issue);
    }
}