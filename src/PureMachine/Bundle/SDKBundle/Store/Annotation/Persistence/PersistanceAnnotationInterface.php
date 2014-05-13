<?php

namespace PureMachine\Bundle\SDKBundle\Store\Annotation\Persistence;

interface PersistanceAnnotationInterface
{

    public function getPackage();

    public function getAdapter();

}