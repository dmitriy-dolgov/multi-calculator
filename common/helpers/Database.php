<?php

namespace common\helpers;

class Database
{
    public static function getDsnAttribute($name, $dsn)
    {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    /**
     * Проверяет таблицу $tableClass на наличие в колонке $columnName значения $columnValue.
     * Генерирует новое уникальное значение для колонки $columnName.
     * Для предполагаемых email (текст осдержит `@`) имеет свой алгоритм.
     *
     * @param string $tableClass ActiveRecord класс
     * @param string $columnName название поля в $tableClass
     * @param string $columnValue значение поля $columnName
     * @return string новое уникальное значение для колонки $columnName
     */
    public static function handeParameterElement(string $tableClass,
                                                 string $columnName,
                                                 string $columnValue = 'fake')
    {
        $count = 0;
        $columnValueMod = $columnValue;

        while ($tableClass::findOne([$columnName => $columnValueMod])) {
            ++$count;

            if (strpos($columnValueMod, '@')) {
                $columnValueMod = $count . '' . $columnValue;
            } else {
                $columnValueMod = $columnValue . '_' . $count;
            }
        }

        return $columnValueMod;
    }
}
