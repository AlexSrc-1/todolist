<?php

namespace app\core;

use Exception;

class App
{
    public static $db;
    public static $router;

    /**
     * Starting the router and connecting to the database
     *
     * @throws Exception
     */
    public function run()
    {
        session_start();
        static::$db = new DbConnection();
        static::$router = new Router();
        static::$router->run();
    }
}