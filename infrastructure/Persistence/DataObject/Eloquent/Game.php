<?php
namespace Infrastructure\Persistence\DataObject\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\DataObject\DataObjectInterface;

class Game extends Model implements DataObjectInterface
{
    protected $connection = 'db_common';

    protected $table = 'tw_game_list';

    public $timestamps = false;

    const IS_SHOW_FIELD = 'is_show';

    const ID_FIELD = 'id';

    const APP_LANGUAGE_FIELD = 'app_lang';

    const NAME_FIELD = 'name';

    const INNER_NAME_FIELD = 'in_name';

    const SHOW_STATUS = 1;
}