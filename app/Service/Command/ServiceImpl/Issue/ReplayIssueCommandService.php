<?php
namespace App\Service\Command\ServiceImpl\Issue;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTOAssembler;
use App\Service\ApplicationServeException;
use App\Service\Command\DTO\Issue\ReplyIssueCommand\InputDTO;
use Domain\DomainServeException;
use Domain\Model\Issue\IssueRepository;
use Domain\Model\IssueReply\IssueReplyRepository;
use Domain\Model\IssueReply\IssueReplyContent;
use Domain\Service\UserReplayIssueService;
use Domain\Model\User\UserRepository;

class ReplayIssueCommandService extends AbstractCommandApplicationService
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
        try {
            $issue_reply = (
                new UserReplayIssueService(
                    $this->userRepository,
                    $this->issueRepository,
                    $this->issueReplyRepository,
                    $input->getUserId(),
                    $input->getIssueId(),
                    IssueReplyContent::formText($input->getContent())
                )
            )->execute();

            $output_assembler->compress($issue_reply);
        } catch (DomainServeException $exception) {
            throw new ApplicationServeException($exception->getMessage());
        }
    }
}