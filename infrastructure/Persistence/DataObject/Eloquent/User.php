<?php
namespace Infrastructure\Persistence\DataObject\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\DataObject\DataObjectInterface;

class User extends Model implements DataObjectInterface
{
    protected $table = 'opgroup_user';

    const USER_NAME_FIELD = 'uName';

    const ID_FIELD = 'uId';
}