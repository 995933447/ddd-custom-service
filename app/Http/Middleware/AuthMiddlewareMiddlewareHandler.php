<?php
namespace App\Http\Middleware;

use App\Http\IO\DefaultIOContextFactory;
use App\Http\IO\Response\Error\StatusEnum;
use App\Http\IO\Response\JsonResponse;
use Framework\AbstractInterface\Middleware\AbstractMiddlewareHandler;
use Infrastructure\Encrypting\JwtEncrypting;

class AuthMiddlewareMiddlewareHandler extends AbstractMiddlewareHandler
{
    public function handle(\Closure $next, $data = null)
    {
        if (!$x_token = DefaultIOContextFactory::get()->getRequest()->server('http_x_token')) {
            return JsonResponse::toFailedEnd([], StatusEnum::AUTH_ERROR_CORE, '登录超时, 请重新登录');
        }

        if (is_int($decoded = JwtEncrypting::decodeToken(null, $x_token))) {
            return JsonResponse::toFailedEnd([], StatusEnum::AUTH_ERROR_CORE, '登录超时, 请重新登录');
        }

        session_id(md5($x_token));
        session_start();

        if (empty($_SESSION) || !isset($_SESSION['login_user']) || (int) $_SESSION['login_user']['uId'] !== (int) $decoded['uid']) {
            return JsonResponse::toFailedEnd([], StatusEnum::AUTH_ERROR_CORE, '登录超时, 请重新登录');
        }

        return $next();
    }
}