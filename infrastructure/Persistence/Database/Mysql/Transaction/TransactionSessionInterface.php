<?php
namespace Infrastructure\Persistence\Database\Mysql\Transaction;

interface TransactionSessionInterface
{
    public function atomicallyExecute(\Closure $process);
}