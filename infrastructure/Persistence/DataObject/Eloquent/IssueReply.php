<?php
namespace Infrastructure\Persistence\DataObject\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\DataObject\DataObjectInterface;

class IssueReply extends Model implements DataObjectInterface
{
    protected $connection = 'www';

    protected $table = 'issue_response';

    protected $primaryKey = self::ID_FIELD;

    public $timestamps = false;

    const ID_FIELD = 'response_id';

    const CONTENT_FIELD = 'content';

    const FROM_TYPE_FIELD = 'from_type';

    const FROM_NAME_FIELD = 'from_name';

    const IMAGE_FIELD = 'img';

    const ADD_TIME_FIELD = 'addtime';

    const ISSUE_ID_FIELD = 'issue_id';

    const FROM_CUSTOMER = 1;

    CONST FROM_USER = 2;
}