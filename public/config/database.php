<?php
namespace Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Database {
    public function __construct()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
             'driver'    => 'mysql',
             'host'      => 'localhost',
             'database'  => 'tp_utn',
             'username'  => 'root',
             'password'  => '',
             'charset'   => 'utf8',
             'collation' => 'utf8_unicode_ci',
             'prefix'    => '', 

            // 'driver'    => 'mysql',
            // 'host'      => 'localhost',
            // 'database'  => 'c1300716_utn',
            // 'username'  => 'c1300716_utn',
            // 'password'  => 'FAfi36gero',
            // 'charset'   => 'utf8',
            // 'collation' => 'utf8_unicode_ci',
            // 'prefix'    => '',
        ]);

        // Set the event dispatcher used by Eloquent models... (optional)

        $capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }
}