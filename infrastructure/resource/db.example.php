<?php
return [
    /*'default'=>[
        'driver'    => 'mysql',
        'host'      => 'rm-j6c61n6ayya13s7975o.mysql.rds.aliyuncs.com',
        'database'  => 'db_opgroup',
        'username'  => 'dms',
        'password'  => 'I2jOXlwPTetzH6DH',
        'charset'   => 'utf8'
    ],*/
    'default' => [
        'driver'    => 'mysql',
        'host'      => '',
        'database'  => '',
        'username'  => '',
        'password'  => '',
        'charset'   => 'utf8mb4',
        'options' => [
            PDO::ATTR_PERSISTENT => true
        ]
    ],
    'www' => [
        'driver'    => 'mysql',
        'host'      => '',
        'database'  => 'db_www',
        'username'  => 'dms',
        'password'  => '',
        'charset'   => 'utf8mb4',
        'options' => [
            PDO::ATTR_PERSISTENT => true
        ]
    ],
    'db_common' => [
        'driver'    => 'mysql',
        'host'      => '',
        'database'  => '',
        'username'  => '',
        'password'  => '',
        'charset'   => 'utf8mb4',
        'options' => [
            PDO::ATTR_PERSISTENT => true
        ]
    ]
];