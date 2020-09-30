<?php
namespace App\Command\SwooleServer\Hooks;

class OnWorkerStartHandler
{
    public function __invoke($server, $worker_id)
    {
       static::bootstrapDataObject();
    }

    protected static function bootstrapDataObject()
    {
        $do_bootstrapper = require __DIR__ . "/../../../../bootstrap/data_object_bootstrapper.php";
        $do_bootstrapper();
    }
}