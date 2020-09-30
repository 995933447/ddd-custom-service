<?php
namespace Infrastructure\Persistence\DataObject\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\DataObject\DataObjectInterface;

class QuickReply extends Model implements DataObjectInterface
{
    protected $connection = 'db_common';

    protected $table = 'tw_quick_reply';

    const ID_FIELD = 'id';

    const SORT_FIELD = 'sort';

    const GAME_ID_FIELD = 'game_id';

    const CONTENT_FIELD = 'content';
}