<?php
namespace PureMachine\Bundle\SDKBundle\Store\Annotation\Persistence\Mongo;

use Doctrine\Common\Annotations\Annotation;
use PureMachine\Bundle\SDKBundle\Store\Annotation\Persistence\PersistanceAnnotationInterface;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Id extends Annotation implements PersistanceAnnotationInterface
{

    public $collection = null;

    public function getPackage()
    {
        return $this->collection;
    }

    public function getAdapter()
    {
        return 'PureMachine\Bundle\SDKBundle\Adapter\Mongo\MongoAdapter';
    }

}
