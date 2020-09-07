<?php
namespace intec\core\helpers;

/**
 * Вспомогательный класс для проверки и конвертирования типов.
 * Class Type
 * @package intec\core\helpers
 */
class Type
{
    /**
     * Проверяет, является ли объект строковым значением.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является строкой.
     */
    public static function isString(&$value) {
        return is_string($value);
    }

    /**
     * Проверяет, является ли объект логическим значением.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является логическим.
     */
    public static function isBoolean(&$value) {
        return is_bool($value);
    }

    /**
     * Проверяет, является ли объект массивом.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является массивом.
     */
    public static function isArray(&$value) {
        return is_array($value);
    }

    /**
     * Проверяет, может ли объект быть представлен как массив.
     * @param mixed $value Значение для проверки.
     * @return bool Значение может быть массивом.
     */
    public static function isArrayable(&$value) {
        return Type::isArray($value) ||
            $value instanceof \ArrayIterator ||
            $value instanceof \ArrayAccess;
    }

    /**
     * Проверяет, является ли объект числом или числом в виде строки.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является числом или числом в виде строки.
     */
    public static function isNumeric(&$value) {
        return is_numeric($value);
    }

    /**
     * Проверяет, является ли объект целым числом.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является целым числом.
     */
    public static function isInteger(&$value) {
        return is_int($value);
    }

    /**
     * Проверяет, является ли объект дробным числом.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является дробным числом.
     */
    public static function isFloat(&$value) {
        return is_float($value);
    }

    /**
     * Проверяет, является ли объект числовым типом.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является числовым типом.
     */
    public static function isNumber(&$value) {
        return static::isInteger($value) || static::isFloat($value);
    }

    /**
     * Проверяет, является ли объект объектом.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является объектом.
     */
    public static function isObject(&$value) {
        return is_object($value);
    }

    /**
     * Проверяет, является ли объект функцией.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является функцией.
     */
    public static function isFunction(&$value) {
        return $value instanceof \Closure;
    }

    /**
     * Проверяет, является ли объект скалярной величиной.
     * @param mixed $value Значение для проверки.
     * @return bool Значение является скалярной величиной.
     */
    public static function isScalar(&$value) {
        return is_scalar($value);
    }

    /**
     * Конвертирует значение в строку.
     * @param mixed $value Значение для преобразования.
     * @return string Строка.
     */
    public static function toString($value) {
        return strval($value);
    }

    /**
     * Конвертирует значение в целое число.
     * @param mixed $value Значение для преобразования.
     * @return integer Целое число.
     */
    public static function toInteger($value) {
        return intval($value);
    }

    /**
     * Конвертирует значение в дробное число.
     * @param mixed $value Значение для преобразования.
     * @return float Дробное число.
     */
    public static function toFloat($value) {
        return floatval($value);
    }

    /**
     * Конвертирует значение в логическое значение.
     * @param mixed $value Значение для преобразования.
     * @return bool Логическое значение.
     */
    public static function toBoolean($value) {
        return (bool)$value;
    }

    /**
     * Конвертирует Arrayable в массив.
     * @param mixed $value Значение для преобразования.
     * @return array Массив.
     */
    public static function toArray($value) {
        $result = [];

        if (static::isArrayable($value))
            foreach ($value as $key => $item)
                $result[$key] = $item;

        return $result;
    }
}