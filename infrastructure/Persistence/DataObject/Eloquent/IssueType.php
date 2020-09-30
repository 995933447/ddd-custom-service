<?php
namespace Infrastructure\Persistence\DataObject\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\DataObject\DataObjectInterface;

class IssueType extends Model implements DataObjectInterface
{
    protected $connection = 'www';

    protected $table = 'issue_type';

    const NAME_FIELD = 'name';

    const ID_FIELD = 'type_id';
}