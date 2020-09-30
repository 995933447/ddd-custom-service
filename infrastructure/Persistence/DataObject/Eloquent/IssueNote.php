<?php
namespace Infrastructure\Persistence\DataObject\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\DataObject\DataObjectInterface;

class IssueNote extends Model implements DataObjectInterface
{
    protected $connection = 'www';

    protected $table = 'issue_note';

    protected $primaryKey = self::ID_FIELD;

    public $timestamps = false;

    const ID_FIELD = 'note_id';

    const ISSUE_ID_FIELD = 'issue_id';
}