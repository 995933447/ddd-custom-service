<?php
namespace Infrastructure\Persistence\DataObject\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\DataObject\DataObjectInterface;

class Issue extends Model implements DataObjectInterface
{
    protected $connection = 'www';

    protected $table = 'issue';

    protected $primaryKey = self::ISSUE_ID_FIELD;

    public $timestamps = false;

    const ISSUE_ID_FIELD = 'issue_id';

    const GAME_ID_FIELD = 'game_id';

    const SERVER_ID_FIELD = 'server_id';

    const IP_FIELD = 'ip';

    const TYPE_ID_FIELD = 'type_id';

    const USER_NAME_FIELD = 'user_name';

    const GAME_ROLE_NAME_FIELD = 'role_name';

    const GAME_ROLE_ID_FIELD = 'role_id';

    const LAST_REPLIER_FIELD = 'last_response';

    const ADD_TIME_FIELD = 'addtime';

    const UPDATE_TIME_FIELD = 'updatetime';

    const IS_FOLLOW_FIELD = 'is_star';

    const HAS_REPLY_RESPONSE_FIELD = 'service_response';

    const WORK_DEAL_STATUS_FIELD = 'work_deal';

    const MACHINE_MODEL_FIELD = 'model';

    const MACHINE_OS_VERSION_FIELD = 'systemversion';

    const SDK_VERSION_FIELD = 'sdk_version_code';

    const TITLE_FIELD = 'title';

    const ISSUE_OPENED_DEAL_STATUS = 0;

    const ISSUE_CLOSED_DEAL_STATUS = 1;

    const ISSUE_HAS_RESPONSE = 1;

    const ISSUE_NOT_RESPONSE = 0;

    const WORK_HAS_DEAL_STATUS = 1;

    const WORK_NOT_DEAL_STATUS = 0;

    const IS_FOLLOWED = 1;

    const IS_NOT_FOLLOWED = 0;

    const PC_MACHINE_OS = 1;

    const IOS_MACHINE_OS = 2;

    const ANDROID_MACHINE_OS = 3;

    const WAP_MACHINE_OS = 4;

    const KDDI_MACHINE_NETWORK = 1;

    const UNICOM_MACHINE_NETWORK = 2;

    const CMCC_MACHINE_NETWORK = 3;

    const WIFI_MACHINE_NETWORK = 4;
}