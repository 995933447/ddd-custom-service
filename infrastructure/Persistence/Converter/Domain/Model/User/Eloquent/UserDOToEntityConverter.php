<?php
namespace Infrastructure\Persistence\Converter\Domain\Model\User\Eloquent;

use Domain\Model\User\HashedPassword;
use Infrastructure\Persistence\Converter\DOToDomainEntityConverterInterface;
use Infrastructure\Persistence\DataObject\Eloquent\User as UserEloquent;
use Domain\Model\User\User as UserEntity;

class UserDOToEntityConverter implements DOToDomainEntityConverterInterface
{
    protected $userEloquentDO;

    public function __construct(UserEloquent $user_do)
    {
        $this->userEloquentDO = $user_do;
    }

    public function toEntity(): UserEntity
    {
        return new UserEntity(
            (int) $this->userEloquentDO->uId,
            $this->userEloquentDO->uName,
            HashedPassword::fromHashed($this->userEloquentDO->uPass)
        );
    }
}