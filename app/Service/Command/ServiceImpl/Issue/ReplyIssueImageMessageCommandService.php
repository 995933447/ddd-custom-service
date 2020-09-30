<?php
namespace App\Service\Command\ServiceImpl\Issue;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTOAssembler;
use App\Service\ApplicationServeException;
use App\Service\Command\DTO\Issue\ReplyIssueImageMessageCommand\InputDTO;
use Domain\DomainServeException;
use Domain\Model\Issue\IssueRepository;
use Domain\Model\IssueReply\IssueReplyRepository;
use Domain\Model\IssueReply\IssueReplyContent;
use Domain\Model\User\UserRepository;
use Domain\Service\UserReplayIssueService;
use Infrastructure\Shared\Config\Config;
use OSS\OssClient;

class ReplyIssueImageMessageCommandService extends AbstractCommandApplicationService
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
        $key = Config::get('aliyuncs.accessKeyId');
        $secret = Config::get('aliyuncs.accessKeySecret');
        $end_point = Config::get('aliyuncs.endPoint');
        $bucket = Config::get('aliyuncs.bucket');
        $oss_client = new OssClient($key, $secret, $end_point);

        $array = explode('.', $input->getImageName());
        $extension = array_pop($array);
        $path = "upload/image/" . date('Ym') . '/' . date('dHis') . mt_rand(1, 9999) . '.' . $extension;

        try {
            $oss_client->uploadFile($bucket, $path, $input->getImagePath());
        } catch (ApplicationServeException $e) {
            throw new ApplicationServeException($e->getMessage(), 1);
        }

        try {
            $issue_reply = (
                new UserReplayIssueService(
                    $this->userRepository,
                    $this->issueRepository,
                    $this->issueReplyRepository,
                    $input->getUserId(),
                    $input->getIssueId(),
                    IssueReplyContent::fromImage($path)
                )
            )->execute();
            $output_assembler->compress($issue_reply);
        } catch (DomainServeException $exception) {
            throw new ApplicationServeException($exception->getMessage());
        }
    }
}