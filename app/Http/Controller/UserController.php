<?php

namespace App\Http\Controller;

use App\Http\Controller\Traits\ParseRequestQueryPageTrait;
use App\Http\IO\DefaultIOContextFactory;
use App\Http\IO\Response\Error\StatusEnum;
use App\Http\IO\Response\JsonResponse;
use App\Service\ApplicationServeException;
use App\Service\Command\ServiceImpl\User\LoginCommandService;
use App\Http\Middleware\AuthMiddlewareMiddlewareHandler;
use Infrastructure\Persistence\Repository\Domain\Model\User\Eloquent\UserRepository;
use App\Service\Command\DTO\User\LoginCommand\InputDTO;
use App\Service\Command\DTOAssembler\User\LoginCommand\TokenOutputDTOAssembler;

class UserController
{
    use ParseRequestQueryPageTrait;

    protected $middleware = [
        AuthMiddlewareMiddlewareHandler::class => [
            'exclude' => ['login', 'logout']
        ]
    ];

    public function login()
    {
        $request = DefaultIOContextFactory::get()->getRequest();

        try {
            (new LoginCommandService(new UserRepository()))->execute(
                new InputDTO([
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ]),
                $output_assembler = new TokenOutputDTOAssembler()
            );
        } catch (ApplicationServeException $e) {
            return JsonResponse::toFailedEnd([], StatusEnum::INTERNAL_ERROR_CODE, $e->getMessage());
        }

        return JsonResponse::toSuccessEnd($output_assembler->assemble()->toArray());
    }

    public function info()
    {
        json_response([
            'roles' => ['admin'],
            'name' => $_SESSION['login_user']['uName'],
            'avatar' => '/avatar/admin.gif',
            'introduction' => '暂无',
        ]);
    }

    public function logout()
    {
        json_response();
    }
}