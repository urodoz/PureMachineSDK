<?php

namespace PureMachine\Bundle\SDKBundle\Adapter\Mongo;

use PureMachine\Bundle\SDKBundle\Store\Base\BaseStore;
use PureMachine\Bundle\SDKBundle\Adapter\AdapterInterface;

class MongoAdapter implements AdapterInterface
{

    public static function hydrate(BaseStore $store, $package, $propertyKey)
    {
        $db = Connection::getConnection();
        $collection = $db->$package;

        $record = $collection->findOne(array(
            $propertyKey => call_user_func(array($store, "get".ucfirst($propertyKey))),
        ));

        if ($record) {
            $store->initialize($record);
        }
    }

    public static function save(BaseStore $store, $package, $propertyKey)
    {
        $db = Connection::getConnection();

        //Get data as array
        $serializedStore = json_decode(json_encode($store->serialize()), true);

        /*
         * Try to find the store on database
         */
        $collection = $db->$package;
        $record = $collection->findOne(array(
            $propertyKey => call_user_func(array($store, "get".ucfirst($propertyKey))),
        ));
        if ($record) {
            /*
             * Merging the data, but keeping the priority on the new store
             * The data not sended will be keeped on database to avoid delete
             * data stored on the records not stored as keys on the Store object
             */
            $serializedStore = array_merge($record, $serializedStore);
        }

        $saveReturn = $collection->save($serializedStore);

        /*
         * Return
         */

        return $saveReturn;
    }

}
