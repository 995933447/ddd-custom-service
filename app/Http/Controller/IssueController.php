<?php

namespace App\Http\Controller;

use App\Event\Issue\UserQueryIssuesEvent;
use App\Http\Controller\Traits\ParseRequestQueryPageTrait;
use App\Http\IO\DefaultIOContextFactory;
use App\Http\IO\Response\Error\StatusEnum;
use App\Http\IO\Response\JsonResponse;
use App\Http\Middleware\AuthMiddlewareMiddlewareHandler;
use App\Service\ApplicationServeException;
use App\Service\Command\DTOAssembler\Issue\BatchReplyIssuesCommand\CreatedAtOutputDTOAssembler;
use App\Service\Command\DTOAssembler\Issue\EndDealIssueProcessingCommand\UpdatedAtOutputDTOAssembler;
use App\Service\Command\DTOAssembler\Issue\RemarkIssueCommand\RemarkIdAndCratedAtOutputDTOAssembler;
use App\Service\Command\DTOAssembler\Issue\ReplyIssueCommand\ImageUrlAndCreatedAtOutputDTOAssembler;
use App\Service\Command\DTOAssembler\Issue\ReplyIssueCommand\ReplayIdAndCreatedAtOutputDTOAssembler;
use App\Service\Command\ServiceImpl\Issue\BatchReplyIssuesCommandService;
use App\Service\Command\ServiceImpl\Issue\EndDealIssueProcessingCommandService;
use App\Service\Command\ServiceImpl\Issue\OperateFollowIssueCommandService;
use App\Service\Command\ServiceImpl\Issue\RemarkIssueCommandService;
use App\Service\Command\ServiceImpl\Issue\ReplayIssueCommandService;
use App\Service\Command\ServiceImpl\Issue\ReplyIssueImageMessageCommandService;
use App\Service\Command\ServiceImpl\TransactionCommandService;
use App\Service\InvalidDTOAssembler;
use App\Service\NullDTO;
use App\Service\Query\DTO\Issue\IssueListQuery\InputDTO as IssueListQueryInputDTO;
use App\Service\Query\ServiceImpl\Issue\IssueListQueryService;
use App\Service\Query\ServiceImpl\Issue\IssuesOfUserQueryService;
use App\Service\Query\ServiceImpl\Issue\IssueQueryService;
use App\Service\Query\ServiceImpl\Issue\IssueQuickReplyListQueryService;
use App\Service\Query\ServiceImpl\Issue\IssueTypeListQueryService;
use App\Service\Query\ServiceImpl\Issue\IssueReplayListQueryService;
use App\Service\Query\ServiceImpl\Issue\RemarksOfIssueQueryService;
use Framework\Event\EventDispatcher;
use Infrastructure\Persistence\Repository\Domain\Model\IssueRemark\Eloquent\IssueRemarkRepository;
use Infrastructure\Persistence\Database\Mysql\Transaction\IlluminateTransactionSession;
use Infrastructure\Persistence\Repository\Domain\Model\IssueReply\Eloquent\IssueReplyRepository;
use Infrastructure\Persistence\Repository\Domain\Model\Issue\Eloquent\IssueRepository;
use Infrastructure\Persistence\Repository\Domain\Model\User\Eloquent\UserRepository;
use Infrastructure\Shared\Config\Config;
use Infrastructure\Persistence\Database\DB\DB;
use App\Util\FirebaseCloudMessage\DefaultFirebaseCloudMessageFactory;
use App\Service\Query\DTO\Issue\IssueQuery\InputDTO as IssueQueryInputDTO;
use App\Service\Query\DTO\Issue\IssueReplyListQuery\InputDTO as ReplayListQueryInputDTO;
use App\Service\Query\DTO\Issue\IssueOfUserQuery\InputDTO as IssueOfUserQueryInputDTO;
use App\Service\Query\DTO\Issue\RemarksOfIssueQuery\InputDTO as RemarksOfIssueQueryInputDTO;
use App\Service\Command\DTO\Issue\OperateFollowIssueCommand\InputDTO as OperateFollowIssueCommandInputDto;
use App\Service\Query\DTO\Issue\IssueQuickReplyListQuery\InputDTO as IssueQuickReplyListQueryInputDTO;
use App\Service\Command\DTO\Issue\EndDealIssueProcessingCommand\InputDTO as EndIssueProcessingProgressCommandInputDTO;
use App\Service\Command\DTO\Issue\ReplyIssueCommand\InputDTO as ReplyIssueCommandInputDTO;
use App\Service\Command\DTO\Issue\ReplyIssueImageMessageCommand\InputDTO as ReplyIssueImageCommandInputDTO;
use App\Service\Command\DTO\Issue\RemarkIssueCommand\InputDTO as RemarkIssueCommandInputDTO;
use App\Service\Command\DTO\Issue\BatchReplyIssuesCommand\InputDTO as BatchIssuesCommandInputDTO;

class IssueController
{
    use ParseRequestQueryPageTrait;

    protected $middleware = [
        AuthMiddlewareMiddlewareHandler::class,
    ];

    public function list()
    {
        $request = DefaultIOContextFactory::get()->getRequest();
        [$start_date, $end_date] = $request->input('date_range', [null, null]);

        $query_result = (new IssueListQueryService)->execute(
            new IssueListQueryInputDTO([
                'game_id_list' => $request->input('game_id_list', []),
                'issue_type_id' => (int) $request->input('issue_type_id', 0),
                'issue_status' => (int) $request->input('issue_status', 0),
                'issue_id' => (int) $request->input('issue_id', 0),
                'user_name' => $request->input('user_name', ''),
                'role_name' => $request->input('role_name', ''),
                'service_name' => $request->input('service_name', ''),
                'start_date' => $start_date?: '',
                'end_date' => $end_date?: '',
                'is_followed' => (bool)  $request->input('is_star', false),
                'page' => $this->getQueryPage(),
                'per_page_limit' => $this->getQueryPageLimit()
            ])
        );

        if ($query_result->hasError()) {
            return JsonResponse::toFailedEnd();
        }

        EventDispatcher::dispatch(
            new UserQueryIssuesEvent($_SESSION['login_user']['uId'], $query_result->getValue()['list'])
        );

        return JsonResponse::toSuccessEnd($query_result->getValue());
    }

    public function typeList()
    {
        $query_result = (new IssueTypeListQueryService)->execute(new NullDTO());

        if ($query_result->hasError()) {
            return JsonResponse::toFailedEnd();
        }

        return JsonResponse::toSuccessEnd($query_result->getValue());
    }

    public function detail()
    {
        if (!$issue_id = DefaultIOContextFactory::get()->getRequest()->get('issue_id')) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '请输入issue id');
        }

        $query_result = (new IssueQueryService)->execute(
            new IssueQueryInputDTO(['issue_id' => (int) $issue_id])
        );

        if ($query_result->hasError()) {
            return JsonResponse::toFailedEnd();
        }

        return JsonResponse::toSuccessEnd($query_result->getValue());
    }

    public function replyList()
    {
        $issue_id = (int) DefaultIOContextFactory::get()->getRequest()->get('issue_id');

        if (!$issue_id) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '请输入issue id');
        }

        $query_result = (new IssueReplayListQueryService)->execute(
            new ReplayListQueryInputDTO(['issue_id' => $issue_id])
        );

        if ($query_result->hasError()) {
            return JsonResponse::toFailedEnd();
        }

        return JsonResponse::toSuccessEnd($query_result->getValue());
    }

    public function history()
    {
        $request = DefaultIOContextFactory::get()->getRequest();
        $issue_id = (int) $request->get('issue_id', 0);
        $user_name = $request->get('user_name');

        if (!$issue_id || !$user_name) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, 'issue id 和 用户名 必须传递');
        }

        $query_result = (new IssuesOfUserQueryService)->execute(
            new IssueOfUserQueryInputDTO(['exclude_issue_id' => $issue_id, 'user_name' => $user_name])
        );

        if ($query_result->hasError()) {
            return JsonResponse::toFailedEnd();
        }

        return JsonResponse::toSuccessEnd($query_result->getValue());
    }

    public function reply()
    {
        $request = DefaultIOContextFactory::get()->getRequest();
        $issue_id = $request->input('issue_id', 0);
        $content = $request->input('content', '');

        if (!$issue_id || !$content) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '参数不合法.');
        }

        try {
            (new TransactionCommandService(
                new IlluminateTransactionSession(DB::connection('www')),
                new ReplayIssueCommandService(new UserRepository, new IssueRepository, new IssueReplyRepository)
            ))->execute(
                new ReplyIssueCommandInputDTO([
                    'issue_id' => $issue_id,
                    'user_id' => $_SESSION['login_user']['uId'],
                    'content' => $content
                ]),
                $output_assembler = new ReplayIdAndCreatedAtOutputDTOAssembler
            );
        } catch(ApplicationServeException $exception) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, $exception->getMessage());
        }

        return JsonResponse::toSuccessEnd($output_assembler->assemble()->toArray());
    }

    public function quickReplyList()
    {
        if (is_null($game_id = DefaultIOContextFactory::get()->getRequest()->get('game_id'))) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '缺少参数game id.');
        }

        $query_result = (new IssueQuickReplyListQueryService)->execute(
            new IssueQuickReplyListQueryInputDTO(['game_id' => $game_id])
        );

        if ($query_result->hasError()) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, $query_result->getError()->getMessage());
        }

        return JsonResponse::toSuccessEnd($query_result->getValue());
    }

    public function uploadImage()
    {
        $file = $_FILES['file'];

        $allow_mime = [
            'image/png',
            'image/jpeg',
        ];

        if (!in_array($file['type'], $allow_mime)) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '文件格式不正确');
        }

        if ($file['size'] > (1048576 * 8)) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '文件超出8M，无法上传');
        }

        try {
            (new TransactionCommandService(
                new IlluminateTransactionSession(DB::connection('www')),
                new ReplyIssueImageMessageCommandService(new UserRepository, new IssueRepository, new IssueReplyRepository)
            ))->execute(
                new ReplyIssueImageCommandInputDTO([
                    'issue_id' => DefaultIOContextFactory::get()->getRequest()->input('issue_id'),
                    'user_id' => $_SESSION['login_user']['uId'],
                    'image_name' => $file['name'],
                    'image_path' => $file['tmp_name']
                ]),
                $output_assembler = new ImageUrlAndCreatedAtOutputDTOAssembler()
            );
        } catch(ApplicationServeException $exception) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, $exception->getMessage());
        }

        return JsonResponse::toSuccessEnd($output_assembler->assemble()->toArray());
    }

    public function updateStatus()
    {
        try {
            (new EndDealIssueProcessingCommandService(new IssueRepository))
                ->execute(
                    new EndIssueProcessingProgressCommandInputDTO([
                        'game_id' => ($request = DefaultIOContextFactory::get()->getRequest())->input('game_id'),
                        'issue_id' => $request->input('issue_id')
                    ]),
                    $output_assembler = new UpdatedAtOutputDTOAssembler
                );
        } catch(ApplicationServeException $exception) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, $exception->getMessage());
        }

        return JsonResponse::toSuccessEnd($output_assembler->assemble()->toArray());
    }

    public function noteList()
    {
        $issue_id = (int) DefaultIOContextFactory::get()->getRequest()->get('issue_id', 0);
        if (!$issue_id) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '缺少issue id');
        }

        $query_result = (new RemarksOfIssueQueryService)
            ->execute(new RemarksOfIssueQueryInputDTO(['issue_id' => $issue_id]));

        if ($query_result->hasError()) {
            return JsonResponse::toFailedEnd();
        }

        return JsonResponse::toSuccessEnd($query_result->getValue());
    }

    public function note()
    {
        $request = DefaultIOContextFactory::get()->getRequest();
        $issue_id = $request->input('issue_id');
        $content = $request->input('content');

        if (!$issue_id || !$content) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '参数错误');
        }

        try {
            (new RemarkIssueCommandService(new UserRepository, new IssueRepository, new IssueRemarkRepository))
                ->execute(
                    new RemarkIssueCommandInputDTO([
                        'issue_id' => $issue_id,
                        'content' => $content,
                        'user_id' => $_SESSION['login_user']['uId']
                    ]),
                    $output_assembler = new RemarkIdAndCratedAtOutputDTOAssembler
                );
        } catch (ApplicationServeException $exception) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, $exception->getMessage());
        }

        return JsonResponse::toSuccessEnd($output_assembler->assemble()->toArray());
    }

    public function batchReply()
    {
        $request = DefaultIOContextFactory::get()->getRequest();
        $issue_id_list = $request->input('issue_id_list');
        $content = $request->input('content', '');
        $work_deal = (bool) $request->input('work_deal', false);

        try {
            (new TransactionCommandService(
                new IlluminateTransactionSession(DB::connection('www')),
                new BatchReplyIssuesCommandService(new UserRepository, new IssueRepository, new IssueReplyRepository)
            ))->execute(
                new BatchIssuesCommandInputDTO([
                        'issue_ids' => $issue_id_list,
                        'content' => $content,
                        'is_end_deal_issues' => $work_deal,
                        'user_id' => $_SESSION['login_user']['uId']
                ]),
                $output_assembler = new CreatedAtOutputDTOAssembler
            );
        } catch(ApplicationServeException $exception) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, $exception->getMessage());
        }

        return JsonResponse::toSuccessEnd($output_assembler->assemble()->toArray());
    }

    public function isStar()
    {
        $request = DefaultIOContextFactory::get()->getRequest();

        if (is_null($is_followed = $request->input('is_star'))) {
           return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '缺少参数is_star');
        }

        if (!$issue_id = $request->input('issue_id')) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, '缺少参数issue_id');
        }

        try {
            (new OperateFollowIssueCommandService(new IssueRepository))
                ->execute(
                    new OperateFollowIssueCommandInputDto(['is_followed' => (bool) $is_followed, 'issue_id' => $issue_id]),
                    new InvalidDTOAssembler
                );
        } catch (ApplicationServeException $exception) {
            return JsonResponse::toFailedEnd();
        }

        return JsonResponse::toSuccessEnd();
    }
}