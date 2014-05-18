<?php

namespace PureMachine\Bundle\SDKBundle\Manager;

use PureMachine\Bundle\SDKBundle\Store\Base\BaseStore;
use PureMachine\Bundle\SDKBundle\Exception\StoreException;

class StoreManager
{

    const CONF_STORE_KEY = 'PureMachine_Bundle_SDKBundle_Manager.mapping';
    const CONF_MONGO_KEY = 'PureMachine_Bundle_SDKBundle_Manager.mongo';

    const ALIAS_MONGO = "mongo";

    public static $adapterMap = array(
        'mongo' => '\PureMachine\Bundle\SDKBundle\Adapter\Mongo\MongoAdapter'
    );

    /**
     * Store statically the configuration for the alias
     *
     * @param $alias
     * @param $configuration
     */
    public static function map($configuration)
    {
        StaticManager::storeData(static::CONF_STORE_KEY, $configuration["stores"]);
        //Adapter configurations
        if(isset($configuration["mongo"])) StaticManager::storeData(static::CONF_MONGO_KEY, $configuration["mongo"]);
    }

    public static function hydrate(BaseStore $store, $alias=null)
    {
        list($adapterClass, $package, $id) = static::getAdapter($store, $alias);

        return $adapterClass::hydrate($store, $package, $id);
    }

    public static function save(BaseStore $store, $alias=null)
    {
        list($adapterClass, $package, $id) = static::getAdapter($store, $alias);

        return $adapterClass::save($store, $package, $id);
    }

    public static function remove(BaseStore $store, $alias=null)
    {
        list($adapterClass, $package, $id) = static::getAdapter($store, $alias);

        return $adapterClass::remove($store, $package, $id);
    }

    public static function getAdapter(BaseStore $store, $alias=null)
    {
        $storeMapping = StaticManager::fetchData(static::CONF_STORE_KEY);
        $storeClassname = get_class($store);
        if (isset($storeMapping[$storeClassname])) {
            $classPConfig = $storeMapping[$storeClassname];
            /*
             * Config control
             */
            if (!isset($classPConfig["engines"]) || !isset($classPConfig["package"])) {
                throw new StoreException(
                    "No engines and/or packages has been defined for the class '".$storeClassname."'",
                    StoreException::STORE_007
                );
            }
            if (!isset($classPConfig["id"])) {
                throw new StoreException(
                    "No id has been defined to persist for the class '".$storeClassname."'",
                    StoreException::STORE_007
                );
            }
            if (empty($classPConfig["engines"])) {
                throw new StoreException(
                    "No engines has been configured on the persistance mapping for this class",
                    StoreException::STORE_007
                );
            }

            /**
             * Defining the engine storage from alias or
             * from the default value (first value of config)
             */
            if (!is_null($alias)) {
                if (!in_array($alias, $classPConfig["engines"])) {
                    throw new StoreException(
                        "The engine selected [".$alias."], is not available to be used with the class due to its map",
                        StoreException::STORE_007
                    );
                }
            } else {
                $alias = $classPConfig["engines"][0];
            }

            //The engine is selected
            $selectedEngine = $alias;
            $adapterClass = static::$adapterMap[$selectedEngine];

            return array($adapterClass, $classPConfig["package"], $classPConfig["id"]);
        } else {
            throw new StoreException(
                "The class '".$storeClassname."', has not been mapped to be persisted",
                StoreException::STORE_007
            );
        }
    }

}
