<?php
namespace App\Http\IO\Response;

use App\Http\IO\Response\Error\StatusEnum;
use Framework\AbstractInterface\Http\Server\ResponseInterface;

class JsonResponse
{
    public static function toSuccessEnd($data = [], int $code = StatusEnum::SUCCESS_CODE, string $message = 'success'): ResponseInterface
    {
        $json = [
            'code' => $code,
            'message' => $message
        ];

        $data && $json['data'] = $data;

        return json_output($json);
    }

    public static function toFailedEnd($data = [], int $code = StatusEnum::INTERNAL_ERROR_CODE, string $message = 'failed'): ResponseInterface
    {
        $json = [
            'code' => $code,
            'message' => $message
        ];

        $data && $json['data'] = $data;

        return json_output($json);
    }
}