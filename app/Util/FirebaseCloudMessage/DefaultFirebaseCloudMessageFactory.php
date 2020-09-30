<?php
namespace App\Util\FirebaseCloudMessage;

use Infrastructure\Cache\CacheRepositoryFactory;
use Infrastructure\MessageSender\MessagePusher\FirebaseCloudMessage;
use Infrastructure\Log\FileLogger;

class DefaultFirebaseCloudMessageFactory implements FireBaseCloudMessageFactoryInterface
{
    public static function make(): FirebaseCloudMessage
    {
        return new FirebaseCloudMessage(
            new FileLogger('FirebaseLog'),
            CacheRepositoryFactory::make(CacheRepositoryFactory::REDIS_DRIVER),
            __DIR__ . '/../../../infrastructure/resource'
        );
    }
}