<?php

namespace common\models\traits;

trait ObjectHandling
{
    /*protected static function theClass(){
        return static::class;
    }*/

    public static function getOrFail($objId)
    {
        //$className = static::class;

        if (!$classObj = static::findOne($objId)) {
            throw new \DomainException('No class "' . static::class . '" with id ' . $objId);
        }

        return $classObj;
    }
}
