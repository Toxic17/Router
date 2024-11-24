<?php

namespace Core;

use \Illuminate\Database\Capsule\Manager as Capsul;
use \Illuminate\Events\Dispatcher;
use \Illuminate\Container\Container;

class Database
{
    protected static $config = null;


    public static function getORM()
    {
        self::$config = json_decode(file_get_contents("../config/database.json"),true);

        $type = self::$config['type'];
        $dbname = self::$config['dbname'];
        $username = self::$config['user'];
        $password = self::$config['password'];
        $host = self::$config['host'];
        $port = self::$config['port'];

        $capsule = new Capsul;

        $capsule->addConnection([
            'driver' => $type,
            'host' => $host,
            'database' => $dbname,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}