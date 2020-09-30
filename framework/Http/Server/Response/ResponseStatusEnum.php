<?php
namespace Framework\Http\Server\Response;

final class ResponseStatusEnum
{
    const HTTP_CONTINUE_CODE = 100;
    const HTTP_SWITCHING_PROTOCOLS_CODE = 101;
    const HTTP_PROCESSING_CODE = 102;

    const HTTP_OK_CODE = 200;
    const HTTP_CREATED_CODE = 201;
    const HTTP_ACCEPTED_CODE = 202;
    const HTTP_NONAUTHORITATIVE_INFORMATION_CODE = 203;
    const HTTP_NO_CONTENT_CODE = 204;
    const HTTP_RESET_CONTENT_CODE = 205;
    const HTTP_PARTIAL_CONTENT_CODE = 206;
    const HTTP_MULTI_STATUS_CODE = 207;
    const HTTP_ALREADY_REPORTED_CODE = 208;
    const HTTP_IM_USED_CODE = 226;

    const HTTP_MULTIPLE_CHOICES_CODE = 300;
    const HTTP_MOVED_PERMANENTLY_CODE = 301;
    const HTTP_FOUND_CODE = 302;
    const HTTP_SEE_OTHER_CODE = 303;
    const HTTP_NOT_MODIFIED_CODE = 304;
    const HTTP_USE_PROXY_CODE = 305;
    const HTTP_UNUSED_CODE = 306;
    const HTTP_TEMPORARY_REDIRECT_CODE = 307;
    const HTTP_PERMANENT_REDIRECT_CODE = 308;

    const HTTP_BAD_REQUEST_CODE = 400;
    const HTTP_UNAUTHORIZED_CODE = 401;
    const HTTP_PAYMENT_REQUIRED_CODE = 402;
    const HTTP_FORBIDDEN_CODE = 403;
    const HTTP_NOT_FOUND_CODE = 404;
    const HTTP_METHOD_NOT_ALLOWED_CODE = 405;
    const HTTP_NOT_ACCEPTABLE_CODE = 406;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED_CODE = 407;
    const HTTP_REQUEST_TIMEOUT_CODE = 408;
    const HTTP_CONFLICT_CODE = 409;
    const HTTP_GONE_CODE = 410;
    const HTTP_LENGTH_REQUIRED_CODE = 411;
    const HTTP_PRECONDITION_FAILED_CODE = 412;
    const HTTP_REQUEST_ENTITY_TOO_LARGE_CODE = 413;
    const HTTP_REQUEST_URI_TOO_LONG_CODE = 414;
    const HTTP_UNSUPPORTED_MEDIA_TYPE_CODE = 415;
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE_CODE = 416;
    const HTTP_EXPECTATION_FAILED_CODE = 417;
    const HTTP_IM_A_TEAPOT_CODE = 418;
    const HTTP_MISDIRECTED_REQUEST_CODE = 421;
    const HTTP_UNPROCESSABLE_ENTITY_CODE = 422;
    const HTTP_LOCKED_CODE = 423;
    const HTTP_FAILED_DEPENDENCY_CODE = 424;
    const HTTP_UPGRADE_REQUIRED_CODE = 426;
    const HTTP_PRECONDITION_REQUIRED_CODE = 428;
    const HTTP_TOO_MANY_REQUESTS_CODE = 429;
    const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE_CODE = 431;
    const HTTP_CONNECTION_CLOSED_WITHOUT_RESPONSE_CODE = 444;
    const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS_CODE = 451;
    const HTTP_CLIENT_CLOSED_REQUEST_CODE = 499;

    const HTTP_INTERNAL_SERVER_ERROR_CODE = 500;
    const HTTP_NOT_IMPLEMENTED_CODE = 501;
    const HTTP_BAD_GATEWAY_CODE = 502;
    const HTTP_SERVICE_UNAVAILABLE_CODE = 503;
    const HTTP_GATEWAY_TIMEOUT_CODE = 504;
    const HTTP_VERSION_NOT_SUPPORTED_CODE = 505;
    const HTTP_VARIANT_ALSO_NEGOTIATES_CODE = 506;
    const HTTP_INSUFFICIENT_STORAGE_CODE = 507;
    const HTTP_LOOP_DETECTED_CODE = 508;
    const HTTP_NOT_EXTENDED_CODE = 510;
    const HTTP_NETWORK_AUTHENTICATION_REQUIRED_CODE = 511;
    const HTTP_NETWORK_CONNECTION_TIMEOUT_ERROR_CODE = 599;

    protected static $reasons = [
        //Informational 1xx
        ResponseStatusEnum::HTTP_CONTINUE_CODE => 'Continue',
        ResponseStatusEnum::HTTP_SWITCHING_PROTOCOLS_CODE => 'Switching Protocols',
        ResponseStatusEnum::HTTP_PROCESSING_CODE => 'Processing',
        //Successful 2xx
        ResponseStatusEnum::HTTP_OK_CODE => 'OK',
        ResponseStatusEnum::HTTP_CREATED_CODE => 'Created',
        ResponseStatusEnum::HTTP_ACCEPTED_CODE => 'Accepted',
        ResponseStatusEnum::HTTP_NONAUTHORITATIVE_INFORMATION_CODE => 'Non-Authoritative Information',
        ResponseStatusEnum::HTTP_NO_CONTENT_CODE => 'No Content',
        ResponseStatusEnum::HTTP_RESET_CONTENT_CODE => 'Reset Content',
        ResponseStatusEnum::HTTP_PARTIAL_CONTENT_CODE => 'Partial Content',
        ResponseStatusEnum::HTTP_MULTI_STATUS_CODE => 'Multi-Status',
        ResponseStatusEnum::HTTP_ALREADY_REPORTED_CODE => 'Already Reported',
        ResponseStatusEnum::HTTP_IM_USED_CODE => 'IM Used',
        //Redirection 3xx
        ResponseStatusEnum::HTTP_MULTIPLE_CHOICES_CODE => 'Multiple Choices',
        ResponseStatusEnum::HTTP_MOVED_PERMANENTLY_CODE => 'Moved Permanently',
        ResponseStatusEnum::HTTP_FOUND_CODE => 'Found',
        ResponseStatusEnum::HTTP_SEE_OTHER_CODE => 'See Other',
        ResponseStatusEnum::HTTP_NOT_MODIFIED_CODE => 'Not Modified',
        ResponseStatusEnum::HTTP_USE_PROXY_CODE => 'Use Proxy',
        ResponseStatusEnum::HTTP_UNUSED_CODE => '(Unused)',
        ResponseStatusEnum::HTTP_TEMPORARY_REDIRECT_CODE => 'Temporary Redirect',
        ResponseStatusEnum::HTTP_PERMANENT_REDIRECT_CODE => 'Permanent Redirect',
        //Client Error 4xx
        ResponseStatusEnum::HTTP_BAD_REQUEST_CODE => 'Bad Request',
        ResponseStatusEnum::HTTP_UNAUTHORIZED_CODE => 'Unauthorized',
        ResponseStatusEnum::HTTP_PAYMENT_REQUIRED_CODE => 'Payment Required',
        ResponseStatusEnum::HTTP_FORBIDDEN_CODE => 'Forbidden',
        ResponseStatusEnum::HTTP_NOT_FOUND_CODE => 'Not Found',
        ResponseStatusEnum::HTTP_METHOD_NOT_ALLOWED_CODE => 'Method Not Allowed',
        ResponseStatusEnum::HTTP_NOT_ACCEPTABLE_CODE => 'Not Acceptable',
        ResponseStatusEnum::HTTP_PROXY_AUTHENTICATION_REQUIRED_CODE => 'Proxy Authentication Required',
        ResponseStatusEnum::HTTP_REQUEST_TIMEOUT_CODE => 'Request Timeout',
        ResponseStatusEnum::HTTP_CONFLICT_CODE => 'Conflict',
        ResponseStatusEnum::HTTP_GONE_CODE => 'Gone',
        ResponseStatusEnum::HTTP_LENGTH_REQUIRED_CODE => 'Length Required',
        ResponseStatusEnum::HTTP_PRECONDITION_FAILED_CODE => 'Precondition Failed',
        ResponseStatusEnum::HTTP_REQUEST_ENTITY_TOO_LARGE_CODE => 'Request Entity Too Large',
        ResponseStatusEnum::HTTP_REQUEST_URI_TOO_LONG_CODE => 'Request-URI Too Long',
        ResponseStatusEnum::HTTP_UNSUPPORTED_MEDIA_TYPE_CODE => 'Unsupported Media Type',
        ResponseStatusEnum::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE_CODE => 'Requested Range Not Satisfiable',
        ResponseStatusEnum::HTTP_EXPECTATION_FAILED_CODE => 'Expectation Failed',
        ResponseStatusEnum::HTTP_IM_A_TEAPOT_CODE => 'I\'m a teapot',
        ResponseStatusEnum::HTTP_MISDIRECTED_REQUEST_CODE => 'Misdirected Request',
        ResponseStatusEnum::HTTP_UNPROCESSABLE_ENTITY_CODE => 'Unprocessable Entity',
        ResponseStatusEnum::HTTP_LOCKED_CODE => 'Locked',
        ResponseStatusEnum::HTTP_FAILED_DEPENDENCY_CODE => 'Failed Dependency',
        ResponseStatusEnum::HTTP_UPGRADE_REQUIRED_CODE => 'Upgrade Required',
        ResponseStatusEnum::HTTP_PRECONDITION_REQUIRED_CODE => 'Precondition Required',
        ResponseStatusEnum::HTTP_TOO_MANY_REQUESTS_CODE => 'Too Many Requests',
        ResponseStatusEnum::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE_CODE => 'Request Header Fields Too Large',
        ResponseStatusEnum::HTTP_CONNECTION_CLOSED_WITHOUT_RESPONSE_CODE => 'Connection Closed Without Response',
        ResponseStatusEnum::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS_CODE => 'Unavailable For Legal Reasons',
        ResponseStatusEnum::HTTP_CLIENT_CLOSED_REQUEST_CODE => 'Client Closed Request',
        //RouterInterface Error 5xx
        ResponseStatusEnum::HTTP_INTERNAL_SERVER_ERROR_CODE => 'Internal RouterInterface Error',
        ResponseStatusEnum::HTTP_NOT_IMPLEMENTED_CODE => 'Not Implemented',
        ResponseStatusEnum::HTTP_BAD_GATEWAY_CODE => 'Bad Gateway',
        ResponseStatusEnum::HTTP_SERVICE_UNAVAILABLE_CODE => 'Service Unavailable',
        ResponseStatusEnum::HTTP_GATEWAY_TIMEOUT_CODE => 'Gateway Timeout',
        ResponseStatusEnum::HTTP_VERSION_NOT_SUPPORTED_CODE => 'HTTP Version Not Supported',
        ResponseStatusEnum::HTTP_VARIANT_ALSO_NEGOTIATES_CODE => 'Variant Also Negotiates',
        ResponseStatusEnum::HTTP_INSUFFICIENT_STORAGE_CODE => 'Insufficient Storage',
        ResponseStatusEnum::HTTP_LOOP_DETECTED_CODE => 'Loop Detected',
        ResponseStatusEnum::HTTP_NOT_EXTENDED_CODE => 'Not Extended',
        ResponseStatusEnum::HTTP_NETWORK_AUTHENTICATION_REQUIRED_CODE => 'Network Authentication Required',
        ResponseStatusEnum::HTTP_NETWORK_CONNECTION_TIMEOUT_ERROR_CODE => 'Network Connect Timeout Error',
    ];


    public static function getReason(int $statusCode): string
    {
        if (isset(static::$reasons[$statusCode])) {
            return static::$reasons[$statusCode];
        }

        throw new \InvalidArgumentException();
    }
}