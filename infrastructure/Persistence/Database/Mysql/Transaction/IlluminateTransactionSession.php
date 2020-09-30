<?php
namespace Infrastructure\Persistence\Database\Mysql\Transaction;

use Illuminate\Database\ConnectionInterface;

class IlluminateTransactionSession implements TransactionSessionInterface
{
    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function atomicallyExecute(\Closure $process)
    {
        $this->db->transaction($process);
    }
}