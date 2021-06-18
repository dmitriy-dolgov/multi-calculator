<?php

namespace common\helpers;

class DateTimeHelper
{
    public static function getAgeByBirthday($birthDay)
    {
        $dob = new DateTime($birthDay);
        $today = new DateTime;
        return $today->diff($dob);
    }
}
