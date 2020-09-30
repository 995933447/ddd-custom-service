<?php

use App\Http\IO\DefaultIOContextFactory;
use Infrastructure\Shared\Config\Config;
use App\Http\IO\Response\Error\StatusEnum;

function path_redirect($url, $suffix = true)
{
    $url_suffix = Config::get('base.url_suffix') ?? 'html';
    redirect($url . ($suffix ? '.' . $url_suffix : null));
}


function redirect($url)
{
    DefaultIOContextFactory::get()->getResponse()->redirect($url);
}

function json_response($data = [], $code = StatusEnum::SUCCESS_CODE, $message = 'success')
{
    $json = [
        'code' => $code,
        'message' => $message
    ];

    $data && $json['data'] = $data;

    json_output($json);
}

function json_output($array): \Framework\AbstractInterface\Http\Server\ResponseInterface
{
    return DefaultIOContextFactory::get()->getResponse()->json($array)->send();
}

/**
 * @param $method
 * @param $url
 * @param $data
 * @param array $option
 * @return bool|string
 * 支持更多CURL请求方法，如PUT、DELETE等
 */
function custom_curl($method, $url, $data, $option = [])
{
    $option['timeout'] = $option['timeout'] ?? 30;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_TIMEOUT, $option['timeout']);
    $option['header'] && curl_setopt($ch, CURLOPT_HTTPHEADER, $option['header']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

/**
 * @return mixed
 */
function get_client_ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * @param array $array
 * @return string
 * 构建URL的Query参数，并且不转码里面的字符
 */
function build_query_without_encode($array = [])
{
    $query = [];
    foreach ($array as $key => $val) {
        $query[] = $key . '=' . $val;
    }

    return implode('&', $query);
}

/**
 * 随机字符串
 * @param int $length
 * @return string
 */
function random_string($length = 16)
{
    static $chars = 'abcdefghijklmnopqrstuvwxyzABDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $str;
}