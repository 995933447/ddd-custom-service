<?php
namespace Domain\Model\Issue;

use Domain\AbstractValueObject;

class IssueStatus extends AbstractValueObject
{
    const OPEN_STATUS = 0;

    const CLOSE_STATUS = 1;

    protected $status;

    protected function __construct(int $status)
    {
        $this->status = $status;
    }

    public static function beOpened(): self
    {
        return new static(static::OPEN_STATUS);
    }

    public static function beClosed(): self
    {
        return new static(static::CLOSE_STATUS);
    }

    public function equalsTo(self $status)
    {
        return $this->status === $status->status;
    }
}