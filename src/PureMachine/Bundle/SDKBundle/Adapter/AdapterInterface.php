<?php

namespace PureMachine\Bundle\SDKBundle\Adapter;

use PureMachine\Bundle\SDKBundle\Store\Base\BaseStore;

interface AdapterInterface
{

    public static function hydrate(BaseStore $store, $package, $propertyKey);

    public static function save(BaseStore $store, $package, $propertyKey);

}
