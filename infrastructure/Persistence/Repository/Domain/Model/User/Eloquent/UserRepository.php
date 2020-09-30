<?php
namespace Infrastructure\Persistence\Repository\Domain\Model\User\Eloquent;

use Domain\Model\User\User;
use Domain\Model\User\UserRepository as UserRepositoryInterface;
use Infrastructure\Persistence\Converter\Domain\Model\User\Eloquent\UserDOToEntityConverter;
use Infrastructure\Persistence\DataObject\Eloquent\User as UserOrm;

class UserRepository implements UserRepositoryInterface
{
    public function findByUsername(string $username): ?User
    {
        $user_orm = UserOrm::query()->where(UserOrm::USER_NAME_FIELD, $username)->first();

        if (is_null($user_orm)) {
            return null;
        }

        return $user = (new UserDOToEntityConverter($user_orm))->toEntity();
    }

    public function findById(int $id): ?User
    {
        $user_orm = UserOrm::query()->where(UserOrm::ID_FIELD, $id)->first();

        if (is_null($user_orm)) {
            return null;
        }

        return $user = (new UserDOToEntityConverter($user_orm))->toEntity();
    }

    public function existsById(int $id): bool
    {
        return UserOrm::query()->where(UserOrm::ID_FIELD, $id)->count() > 0;
    }
}