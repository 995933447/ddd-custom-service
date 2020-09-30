<?php
namespace App\Service\Command\ServiceImpl\Issue;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTOAssembler;
use App\Service\ApplicationServeException;
use App\Service\Command\DTO\Issue\RemarkIssueCommand\InputDTO;
use Domain\Model\Issue\IssueRepository;
use Domain\Model\IssueRemark\IssueRemark;
use Domain\Model\User\UserRepository;
use Domain\Model\IssueRemark\IssueRemarkRepository;

class RemarkIssueCommandService extends AbstractCommandApplicationService
{
    protected $issueRemarkRepository;

    protected $issueRepository;

    protected $userRepository;

    public function __construct(UserRepository $user_repository, IssueRepository $issue_repository, IssueRemarkRepository $issue_remark_repository)
    {
        $this->userRepository = $user_repository;
        $this->issueRepository = $issue_repository;
        $this->issueRemarkRepository = $issue_remark_repository;
    }

    protected function handle(InputDTO $input, AbstractDTOAssembler $output_assembler): void
    {
        if (!$this->userRepository->existsById($input->getUserId())) {
            throw new ApplicationServeException('用户不存在.');
        }

        if (!$this->issueRepository->exitById($input->getIssueId())) {
            throw new ApplicationServeException('工单不存在.');
        }

        $issue_remark = IssueRemark::create($input->getIssueId(), $input->getContent(), new \DateTimeImmutable(), $input->getUserId());

        $this->issueRemarkRepository->save($issue_remark);

        $output_assembler->compress($issue_remark);
    }
}