<?php

namespace App\Http\Controller;

use App\Http\Controller\Traits\ParseRequestQueryPageTrait;
use App\Service\NullDTO;
use App\Service\Query\ServiceImpl\Game\GameListQueryService;
use App\Http\Middleware\AuthMiddlewareMiddlewareHandler;
use App\Http\IO\Response\JsonResponse;

class GameController
{
    use ParseRequestQueryPageTrait;

    protected $middleware = [
        AuthMiddlewareMiddlewareHandler::class,
    ];

    public function list()
    {
        /**
         * To example: CQRS architecture.All code will modify like that.
         */
        $query_result = (new GameListQueryService)->execute(new NullDTO());

        if ($query_result->hasError()) {
            throw $query_result->getError();
        }

        JsonResponse::toSuccessEnd($query_result->getValue());
    }
}