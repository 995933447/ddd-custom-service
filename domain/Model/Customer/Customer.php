<?php
namespace Domain\Model\Customer;

use Domain\AbstractAggregateRoot;

class Customer extends AbstractAggregateRoot
{
    protected $id;

    protected $userName;
}