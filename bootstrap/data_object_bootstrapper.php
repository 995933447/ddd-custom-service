<?php
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Events\Dispatcher;
use Infrastructure\Shared\Config\Config;

return function () {
    $manager = new Manager();

    $db_list = Config::get('db');

    foreach ($db_list as $db_name => $db_value) {
        $manager->addConnection($db_value, $db_name);
    }

    $dispatcher = new Dispatcher();
    $dispatcher->listen(StatementPrepared::class, function ($event) {
        $event->statement->setFetchMode(PDO::FETCH_ASSOC);
    });

    $manager->setEventDispatcher($dispatcher);

    $manager->setAsGlobal();
    $manager->bootEloquent();
};