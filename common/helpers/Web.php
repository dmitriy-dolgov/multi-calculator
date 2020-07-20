<?php

namespace common\helpers;

class Web
{
    public static function isLocal()
    {
        $domainParts = explode('.', $_SERVER['SERVER_NAME']);
        return end($domainParts) == 'local';
    }
}
