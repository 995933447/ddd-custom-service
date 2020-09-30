<?php
namespace Domain\IssueCategory;

use Domain\AbstractAggregateRoot;

class IssueCategory extends AbstractAggregateRoot
{
    protected $id;

    protected $name;

    protected $status;

    protected $userId;

    protected $created_at;
}