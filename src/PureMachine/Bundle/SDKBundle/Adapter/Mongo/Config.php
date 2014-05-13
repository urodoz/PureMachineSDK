<?php

namespace PureMachine\Bundle\SDKBundle\Adapter\Mongo;


class Config
{

    static public $host='localhost';

    static public $database;

    static public $port=27017;

    /**
     * @return \MongoDB
     */
    static public function getConnection()
    {
        $connString = "mongodb://".static::$host.":".static::$port;
        $m = new \Mongo($connString);
        return $m->selectDB(static::$database);
    }


} 