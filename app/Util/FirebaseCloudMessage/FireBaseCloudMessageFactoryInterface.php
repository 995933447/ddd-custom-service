<?php
namespace App\Util\FirebaseCloudMessage;

use Infrastructure\MessageSender\MessagePusher\FirebaseCloudMessage;

interface FireBaseCloudMessageFactoryInterface
{
    public static function make(): FirebaseCloudMessage;
}