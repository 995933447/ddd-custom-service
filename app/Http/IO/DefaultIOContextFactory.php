<?php
namespace App\Http\IO;

use Framework\AbstractInterface\Http\Server\IoContextInterface;
use Framework\Http\Server\IOContext;

class DefaultIOContextFactory implements IOContextFactoryInterface
{
    public static function get(): IoContextInterface
    {
        return IOContext::instance();
    }
}