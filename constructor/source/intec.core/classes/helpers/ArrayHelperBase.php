<?php
namespace intec\core\helpers;

use intec\Core;
use intec\core\base\Arrayable;
use intec\core\base\InvalidParamException;

class ArrayHelperBase
{
    public static function toArray($object, $properties = [], $recursive = true)
    {
        if (is_array($object)) {
            if ($recursive) {
                foreach ($object as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        $object[$key] = static::toArray($value, $properties, true);
                    }
                }
            }

            return $object;
        } elseif (is_object($object)) {
            if (!empty($properties)) {
                $className = get_class($object);
                if (!empty($properties[$className])) {
                    $result = [];
                    foreach ($properties[$className] as $key => $name) {
                        if (is_int($key)) {
                            $result[$name] = $object->$name;
                        } else {
                            $result[$key] = static::getValue($object, $name);
                        }
                    }

                    return $recursive ? static::toArray($result, $properties) : $result;
                }
            }
            if ($object instanceof Arrayable) {
                $result = $object->toArray([], [], $recursive);
            } else {
                $result = [];
                foreach ($object as $key => $value) {
                    $result[$key] = $value;
                }
            }

            return $recursive ? static::toArray($result, $properties) : $result;
        } else {
            return [$object];
        }
    }

    public static function merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if ($v instanceof UnsetArrayValue) {
                    unset($res[$k]);
                } elseif ($v instanceof ReplaceArrayValue) {
                    $res[$k] = $v->value;
                } elseif (is_int($k)) {
                    if (isset($res[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }

    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array)) ) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            return $array->$key;
        } elseif (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }

    public static function setValue(&$array, $key, $value, $saveValue = true)
    {
        if ($key === null)
            return false;

        if (!is_array($array))
            return false;

        if ($key instanceof \Closure) {
            return $key($array, $value);
        }

        if (is_array($key)) {
            $length = count($key);

            for ($index = 0; $index < $length; $index++) {
                $keyPart = $key[$index];
                $keyLast = $index == ($length - 1);

                if ($keyLast) {
                    $array[$keyPart] = $value;
                    return true;
                } else {
                    if (ArrayHelper::keyExists($keyPart, $array)) {
                        $arrayPart = &$array[$keyPart];

                        if (!is_array($arrayPart)) {
                            $array[$keyPart] = $saveValue ? ['' => $array[$keyPart]] : [];
                        }

                        $array = &$array[$keyPart];

                        unset($arrayPart);
                    } else {
                        $array[$keyPart] = [];
                        $array = &$array[$keyPart];
                    }
                }
            }
        }

        if (is_object($array)) {
            if (!empty($key)) {
                $array->$key = $value;
                return true;
            } else {
                return false;
            }
        }

        $key = explode('.', $key);
        return static::setValue($array, $key, $value);
    }

    public static function unsetValue(&$array, $key)
    {
        if ($key === null)
            return;

        if (!is_array($array))
            return;

        if ($key instanceof \Closure) {
            $key($array);
            return;
        }

        if (is_array($key)) {
            $length = count($key);

            for ($index = 0; $index < $length; $index++) {
                $keyPart = $key[$index];
                $keyLast = $index == ($length - 1);

                if ($keyLast) {
                    unset($array[$keyPart]);
                    return;
                } else {
                    if (ArrayHelper::keyExists($keyPart, $array)) {
                        $arrayPart = &$array[$keyPart];

                        if (is_array($arrayPart)) {
                            $array = &$array[$keyPart];
                        } else {
                            return;
                        }

                        unset($arrayPart);
                    }
                }
            }
        }

        if (is_object($array)) {
            if (!empty($key)) {
                unset($array->$key);
                return;
            }
        }

        $key = explode('.', $key);
        static::unsetValue($array, $key);
        return;
    }

    public static function remove(&$array, $key, $default = null)
    {
        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            $value = $array[$key];
            unset($array[$key]);

            return $value;
        }

        return $default;
    }

    public static function removeValue(&$array, $value)
    {
        $result = [];
        if (is_array($array)) {
            foreach ($array as $key => $val) {
                if ($val === $value) {
                    $result[$key] = $val;
                    unset($array[$key]);
                }
            }
        }
        return $result;
    }

    public static function index($array, $key, $groups = [])
    {
        $result = [];
        $groups = (array)$groups;

        foreach ($array as $element) {
            $lastArray = &$result;

            foreach ($groups as $group) {
                $value = static::getValue($element, $group);
                if (!array_key_exists($value, $lastArray)) {
                    $lastArray[$value] = [];
                }
                $lastArray = &$lastArray[$value];
            }

            if ($key === null) {
                if (!empty($groups)) {
                    $lastArray[] = $element;
                }
            } else {
                $value = static::getValue($element, $key);
                if ($value !== null) {
                    if (is_float($value)) {
                        $value = (string) $value;
                    }
                    $lastArray[$value] = $element;
                }
            }
            unset($lastArray);
        }

        return $result;
    }

    public static function getColumn($array, $name, $keepKeys = true)
    {
        $result = [];
        if ($keepKeys) {
            foreach ($array as $k => $element) {
                $result[$k] = static::getValue($element, $name);
            }
        } else {
            foreach ($array as $element) {
                $result[] = static::getValue($element, $name);
            }
        }

        return $result;
    }

    public static function map($array, $from, $to, $group = null)
    {
        $result = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $from);
            $value = static::getValue($element, $to);
            if ($group !== null) {
                $result[static::getValue($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public static function keyExists($key, $array, $caseSensitive = true)
    {
        if ($caseSensitive) {
            // Function `isset` checks key faster but skips `null`, `array_key_exists` handles this case
            // http://php.net/manual/en/function.array-key-exists.php#107786
            return isset($array[$key]) || array_key_exists($key, $array);
        } else {
            foreach (array_keys($array) as $k) {
                if (strcasecmp($key, $k) === 0) {
                    return true;
                }
            }

            return false;
        }
    }

    public static function multisort(&$array, $key, $direction = SORT_ASC, $sortFlag = SORT_REGULAR)
    {
        $keys = is_array($key) ? $key : [$key];
        if (empty($keys) || empty($array)) {
            return;
        }
        $n = count($keys);
        if (is_scalar($direction)) {
            $direction = array_fill(0, $n, $direction);
        } elseif (count($direction) !== $n) {
            throw new InvalidParamException('The length of $direction parameter must be the same as that of $keys.');
        }
        if (is_scalar($sortFlag)) {
            $sortFlag = array_fill(0, $n, $sortFlag);
        } elseif (count($sortFlag) !== $n) {
            throw new InvalidParamException('The length of $sortFlag parameter must be the same as that of $keys.');
        }
        $args = [];
        foreach ($keys as $i => $key) {
            $flag = $sortFlag[$i];
            $args[] = static::getColumn($array, $key);
            $args[] = $direction[$i];
            $args[] = $flag;
        }

        // This fix is used for cases when main sorting specified by columns has equal values
        // Without it it will lead to Fatal Error: Nesting level too deep - recursive dependency?
        $args[] = range(1, count($array));
        $args[] = SORT_ASC;
        $args[] = SORT_NUMERIC;

        $args[] = &$array;
        call_user_func_array('array_multisort', $args);
    }

    public static function htmlEncode($data, $valuesOnly = true, $charset = null)
    {
        if ($charset === null) {
            $charset = Core::$app ? Core::$app->charset : 'UTF-8';
        }
        $d = [];
        foreach ($data as $key => $value) {
            if (!$valuesOnly && is_string($key)) {
                $key = htmlspecialchars($key, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
            }
            if (is_string($value)) {
                $d[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
            } elseif (is_array($value)) {
                $d[$key] = static::htmlEncode($value, $valuesOnly, $charset);
            } else {
                $d[$key] = $value;
            }
        }

        return $d;
    }

    public static function htmlDecode($data, $valuesOnly = true)
    {
        $d = [];
        foreach ($data as $key => $value) {
            if (!$valuesOnly && is_string($key)) {
                $key = htmlspecialchars_decode($key, ENT_QUOTES);
            }
            if (is_string($value)) {
                $d[$key] = htmlspecialchars_decode($value, ENT_QUOTES);
            } elseif (is_array($value)) {
                $d[$key] = static::htmlDecode($value);
            } else {
                $d[$key] = $value;
            }
        }

        return $d;
    }

    public static function isAssociative($array, $allStrings = true)
    {
        if (!is_array($array) || empty($array)) {
            return false;
        }

        if ($allStrings) {
            foreach ($array as $key => $value) {
                if (!is_string($key)) {
                    return false;
                }
            }
            return true;
        } else {
            foreach ($array as $key => $value) {
                if (is_string($key)) {
                    return true;
                }
            }
            return false;
        }
    }

    public static function isIndexed($array, $consecutive = false)
    {
        if (!is_array($array)) {
            return false;
        }

        if (empty($array)) {
            return true;
        }

        if ($consecutive) {
            return array_keys($array) === range(0, count($array) - 1);
        } else {
            foreach ($array as $key => $value) {
                if (!is_int($key)) {
                    return false;
                }
            }
            return true;
        }
    }

    public static function isIn($needle, $haystack, $strict = false)
    {
        if ($haystack instanceof \Traversable) {
            foreach ($haystack as $value) {
                if ($needle == $value && (!$strict || $needle === $value)) {
                    return true;
                }
            }
        } elseif (is_array($haystack)) {
            return in_array($needle, $haystack, $strict);
        } else {
            throw new InvalidParamException('Argument $haystack must be an array or implement Traversable');
        }

        return false;
    }

    public static function isTraversable($var)
    {
        return is_array($var) || $var instanceof \Traversable;
    }

    public static function isSubset($needles, $haystack, $strict = false)
    {
        if (is_array($needles) || $needles instanceof \Traversable) {
            foreach ($needles as $needle) {
                if (!static::isIn($needle, $haystack, $strict)) {
                    return false;
                }
            }
            return true;
        } else {
            throw new InvalidParamException('Argument $needles must be an array or implement Traversable');
        }
    }

    public static function filter($array, $filters)
    {
        $result = [];
        $forbiddenVars = [];

        foreach ($filters as $var) {
            $keys = explode('.', $var);
            $globalKey = $keys[0];
            $localKey = isset($keys[1]) ? $keys[1] : null;

            if ($globalKey[0] === '!') {
                $forbiddenVars[] = [
                    substr($globalKey, 1),
                    $localKey,
                ];
                continue;
            }

            if (empty($array[$globalKey])) {
                continue;
            }
            if ($localKey === null) {
                $result[$globalKey] = $array[$globalKey];
                continue;
            }
            if (!isset($array[$globalKey][$localKey])) {
                continue;
            }
            if (!array_key_exists($globalKey, $result)) {
                $result[$globalKey] = [];
            }
            $result[$globalKey][$localKey] = $array[$globalKey][$localKey];
        }

        foreach ($forbiddenVars as $var) {
            list($globalKey, $localKey) = $var;
            if (array_key_exists($globalKey, $result)) {
                unset($result[$globalKey][$localKey]);
            }
        }

        return $result;
    }

    /**
     * Возвращает значения массива с новыми ключами
     * @param array $array
     * @return array
     */
    public static function getValues($array)
    {
        if (Type::isArrayable($array)) {
            return array_values($array);
        }

        return [];
    }

    /**
     * Возвращает первое значение массива.
     * @param $array
     * @return mixed
     */
    public static function getFirstValue($array)
    {
        if (Type::isArrayable($array))
            return reset($array);

        return false;
    }

    /**
     * Возвращает массив ключей массива
     *
     * @param array $array
     * @return array
     */
    public static function getKeys($array)
    {
        if (Type::isArrayable($array)) {
            return array_keys($array);
        }

        return [];
    }

    /**
     * Возвращает первый ключ массива.
     * @param $array
     * @return mixed
     */
    public static function getFirstKey($array)
    {
        if (Type::isArrayable($array)) {
            reset($array);
            return key($array);
        }

        return false;
    }

    /**
     * Убирает 1 элемент массива с начала и возвращает его
     *
     * @param array $array
     * @return mixed
     */
    public static function shift(&$array)
    {
        return array_shift($array);
    }

    /**
     * Добавляет элементы в начало массива
     *
     * @param array $array
     * @param mixed $variable
     * @return int
     */
    public static function unshift(&$array, $variable)
    {
        return array_unshift($array, $variable);
    }

    /**
     * Возвращает часть массива.
     *
     * @param array $array
     * @param integer $offset
     * @param integer|null $length
     * @param boolean $preserveKeys
     * @return array
     */
    public static function slice(&$array, $offset, $length = null, $preserveKeys = false)
    {
        return array_slice($array, $offset, $length, $preserveKeys);
    }

    /**
     * Создает массив и заполняет его значениями.
     *
     * @param integer $start
     * @param integer $count
     * @param mixed $value
     * @return array
     */
    public static function fill($start, $count, $value)
    {
        return array_fill($start, $count, $value);
    }

    /**
     * Меняет местами ключи массива с их значениями.
     * @param array $array Массив для смены.
     * @return array
     */
    public static function flip(&$array)
    {
        return array_flip($array);
    }

    /**
     * Возвращает значение из диапазона.
     * @param array $range
     * @param mixed $value
     * @param bool $strict
     * @param bool $correct
     * @return mixed|null
     */
    public static function fromRange($range, $value, $strict = false, $correct = true)
    {
        if (!Type::isArrayable($range))
            return null;

        if (static::isIn($value, $range, $strict)) {
            return $value;
        } else if ($correct) {
            return static::getFirstValue($range);
        }

        return null;
    }

    /**
     * Заменяет ключи в массиве на другие.
     * @param array $array Массив, ключи которого необходимо заменить.
     * @param array $comparison Какие ключи необходимо заменить.
     * @return array
     */
    public static function replaceKeys($array, $comparison)
    {
        $result = [];

        if (!Type::isArrayable($array) || !Type::isArrayable($comparison))
            return $result;

        foreach ($array as $key => $value) {
            if (static::keyExists($key, $comparison)) {
                $result[$comparison[$key]] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public static function convertEncoding($array, $encodingTo = null, $encodingFrom = null)
    {
        $result = [];

        if (Type::isArray($array))
            $result = Encoding::convert($array, $encodingTo, $encodingFrom);

        return $result;
    }

    /**
     * Проверяет массив на пустоту, а так же если установлен доп. параметр, то элементы массива тоже.
     * @param array $array Массив для проверки.
     * @param bool|\Closure $checkItems Флаг проверки элементов.
     * - false - Не проверять.
     * - true - Проверять элементы с помощью empty().
     * - \Closure - Собственная функция проверки.
     * @return bool Массив пуст.
     */
    public static function isEmpty($array, $checkItems = false)
    {
        if (!Type::isArrayable($array))
            return true;

        if (!$checkItems)
            return empty($array);

        if (!Type::isFunction($checkItems))
            $checkItems = function ($key, $item) {
                return empty($item);
            };

        foreach ($array as $key => $item) {
            $result = $checkItems($key, $item);

            if (!$result)
                return false;
        }

        return true;
    }
}
