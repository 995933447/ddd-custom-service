<?php
namespace Domain\Model\IssueReply;

use Domain\AbstractValueObject;

class ReplyIssueAuthorRole extends AbstractValueObject
{
    const CUSTOMER_ROLE = 0;

    const USER_ROLE = 1;

    protected $role;

    protected function __construct(int $role)
    {
        $this->role = $role;
    }

    public static function beCustomer(): self
    {
        return new static(static::CUSTOMER_ROLE);
    }

    public static function beUser(): self
    {
        return new static(static::USER_ROLE);
    }
}