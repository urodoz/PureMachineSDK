<?php

namespace PureMachine\Bundle\SDKBundle\Adapter\Mongo;

use PureMachine\Bundle\SDKBundle\Manager\StaticManager;
use PureMachine\Bundle\SDKBundle\Manager\StoreManager;

class Connection
{

    public static $host='localhost';

    public static $database;

    public static $port=27017;

    public static $connection;

    /**
     * @return \MongoDB
     */
    public static function getConnection()
    {
        if(static::$connection) return static::$connection;
        $conf = StaticManager::fetchData(StoreManager::CONF_MONGO_KEY);

        $connString = "mongodb://".$conf["host"].":".$conf["port"];
        $m = new \MongoClient($connString);
        static::$connection = $m->$conf["database"];

        return static::$connection;
    }

}
