<?php
namespace App\Service\Command\ServiceImpl\Issue;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTOAssembler;
use App\Service\ApplicationServeException;
use App\Service\Command\DTO\Issue\BatchReplyIssuesCommand\InputDTO;
use Domain\DomainServeException;
use Domain\Model\Issue\IssueProcessingProgress;
use Domain\Model\Issue\IssueRepository;
use Domain\Model\IssueReply\IssueReplyRepository;
use Domain\Model\IssueReply\IssueReplyContent;
use Domain\Model\User\UserRepository;
use Domain\Service\UserReplayIssueService;

class BatchReplyIssuesCommandService extends AbstractCommandApplicationService
{
    protected $userRepository;

    protected $issueRepository;

    protected $issueReplyRepository;

    public function __construct(UserRepository $user_repository, IssueRepository $issue_repository, IssueReplyRepository $issue_reply_repository)
    {
        $this->userRepository = $user_repository;
        $this->issueRepository = $issue_repository;
        $this->issueReplyRepository = $issue_reply_repository;
    }

    protected function handle(InputDTO $input, AbstractDTOAssembler $output_assembler): void
    {
        $issue_replies = [];
        foreach ($input->getIssueIds() as $issue_id) {
            try {
                $issue_replies[] = (
                    new UserReplayIssueService(
                        $this->userRepository,
                        $this->issueRepository,
                        $this->issueReplyRepository,
                        $input->getUserId(),
                        $issue_id,
                        IssueReplyContent::formText($input->getContent())
                    )
                )->execute();
            } catch (DomainServeException $exception) {
                throw new ApplicationServeException($exception->getMessage());
            }
        }

        $issues = $this->issueRepository->getByIds($input->getIssueIds());
        if ($input->getIsEndDealIssues()) {
            foreach ($issues as $issue) {
                $issue->updateProcessProgress(IssueProcessingProgress::beFinish());
                $this->issueRepository->save($issue);
            }
        }

        $output_assembler->compress(['issues' => $issues, 'replies' => $issue_replies]);
    }
}