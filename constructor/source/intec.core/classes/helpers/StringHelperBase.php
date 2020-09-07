<?php
namespace intec\core\helpers;

use intec\Core;
use intec\core\base\Arrayable;

/**
 * BaseStringHelper provides concrete implementation for [[StringHelper]].
 *
 * Do not use BaseStringHelper. Use [[StringHelper]] instead.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alex Makarov <sam@rmcreative.ru>
 * @since 2.0
 */
class StringHelperBase
{
    /**
     * Returns the number of bytes in the given string.
     * This method ensures the string is treated as a byte array by using `mb_strlen()`.
     * @param string $string the string being measured for length
     * @return int the number of bytes in the given string.
     */
    public static function byteLength($string)
    {
        return mb_strlen($string, '8bit');
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     * This method ensures the string is treated as a byte array by using `mb_substr()`.
     * @param string $string the input string. Must be one character or longer.
     * @param int $start the starting position
     * @param int $length the desired portion length. If not specified or `null`, there will be
     * no limit on length i.e. the output will be until the end of the string.
     * @return string the extracted part of string, or FALSE on failure or an empty string.
     * @see http://www.php.net/manual/en/function.substr.php
     */
    public static function byteSubstr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length === null ? mb_strlen($string, '8bit') : $length, '8bit');
    }

    /**
     * Returns the trailing name component of a path.
     * This method is similar to the php function `basename()` except that it will
     * treat both \ and / as directory separators, independent of the operating system.
     * This method was mainly created to work on php namespaces. When working with real
     * file paths, php's `basename()` should work fine for you.
     * Note: this method is not aware of the actual filesystem, or path components such as "..".
     *
     * @param string $path A path string.
     * @param string $suffix If the name component ends in suffix this will also be cut off.
     * @return string the trailing name component of the given path.
     * @see http://www.php.net/manual/en/function.basename.php
     */
    public static function basename($path, $suffix = '')
    {
        if (($len = mb_strlen($suffix)) > 0 && mb_substr($path, -$len) === $suffix) {
            $path = mb_substr($path, 0, -$len);
        }
        $path = rtrim(str_replace('\\', '/', $path), '/\\');
        if (($pos = mb_strrpos($path, '/')) !== false) {
            return mb_substr($path, $pos + 1);
        }

        return $path;
    }

    /**
     * Returns parent directory's path.
     * This method is similar to `dirname()` except that it will treat
     * both \ and / as directory separators, independent of the operating system.
     *
     * @param string $path A path string.
     * @return string the parent directory's path.
     * @see http://www.php.net/manual/en/function.basename.php
     */
    public static function dirname($path)
    {
        $pos = mb_strrpos(str_replace('\\', '/', $path), '/');
        if ($pos !== false) {
            return mb_substr($path, 0, $pos);
        } else {
            return '';
        }
    }
    
    /**
     * Truncates a string to the number of characters specified.
     *
     * @param string $string The string to truncate.
     * @param int $length How many characters from original string to include into truncated string.
     * @param string $suffix String to append to the end of truncated string.
     * @param string $encoding The charset to use, defaults to charset currently used by application.
     * This parameter is available since version 2.0.1.
     * @return string the truncated string.
     */
    public static function truncate($string, $length, $suffix = '...', $encoding = null)
    {
        $encoding = Encoding::resolve($encoding, Encoding::TYPE_DEFAULT, true);

        if (mb_strlen($string, $encoding) > $length) {
            return rtrim(mb_substr($string, 0, $length, $encoding)) . $suffix;
        } else {
            return $string;
        }
    }
    
    /**
     * Truncates a string to the number of words specified.
     *
     * @param string $string The string to truncate.
     * @param int $count How many words from original string to include into truncated string.
     * @param string $suffix String to append to the end of truncated string.
     * This parameter is available since version 2.0.1.
     * @return string the truncated string.
     */
    public static function truncateWords($string, $count, $suffix = '...')
    {
        $words = preg_split('/(\s+)/u', trim($string), null, PREG_SPLIT_DELIM_CAPTURE);
        if (count($words) / 2 > $count) {
            return implode('', array_slice($words, 0, ($count * 2) - 1)) . $suffix;
        } else {
            return $string;
        }
    }

    /**
     * Check if given string starts with specified substring.
     * Binary and multibyte safe.
     *
     * @param string $string Input string
     * @param string $with Part to search inside the $string
     * @param bool $caseSensitive Case sensitive search. Default is true. When case sensitive is enabled, $with must exactly match the starting of the string in order to get a true value.
     * @return bool Returns true if first input starts with second input, false otherwise
     */
    public static function startsWith($string, $with, $caseSensitive = true)
    {
        if (!$bytes = static::byteLength($with)) {
            return true;
        }
        if ($caseSensitive) {
            return strncmp($string, $with, $bytes) === 0;
        } else {
            return mb_strtolower(mb_substr($string, 0, $bytes, '8bit'), Core::$app->charset) === mb_strtolower($with, Core::$app->charset);
        }
    }

    /**
     * Check if given string ends with specified substring.
     * Binary and multibyte safe.
     *
     * @param string $string Input string to check
     * @param string $with Part to search inside of the $string.
     * @param bool $caseSensitive Case sensitive search. Default is true. When case sensitive is enabled, $with must exactly match the ending of the string in order to get a true value.
     * @return bool Returns true if first input ends with second input, false otherwise
     */
    public static function endsWith($string, $with, $caseSensitive = true)
    {
        if (!$bytes = static::byteLength($with)) {
            return true;
        }
        if ($caseSensitive) {
            // Warning check, see http://php.net/manual/en/function.substr-compare.php#refsect1-function.substr-compare-returnvalues
            if (static::byteLength($string) < $bytes) {
                return false;
            }
            return substr_compare($string, $with, -$bytes, $bytes) === 0;
        } else {
            return mb_strtolower(mb_substr($string, -$bytes, mb_strlen($string, '8bit'), '8bit'), Core::$app->charset) === mb_strtolower($with, Core::$app->charset);
        }
    }

    /**
     * Explodes string into array, optionally trims values and skips empty ones
     *
     * @param string $string String to be exploded.
     * @param string $delimiter Delimiter. Default is ','.
     * @param mixed $trim Whether to trim each element. Can be:
     *   - boolean - to trim normally;
     *   - string - custom characters to trim. Will be passed as a second argument to `trim()` function.
     *   - callable - will be called for each value instead of trim. Takes the only argument - value.
     * @param bool $skipEmpty Whether to skip empty strings between delimiters. Default is false.
     * @return array
     * @since 2.0.4
     */
    public static function explode($string, $delimiter = ',', $trim = true, $skipEmpty = false)
    {
        $result = explode($delimiter, $string);
        if ($trim) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif (!is_callable($trim)) {
                $trim = function ($v) use ($trim) {
                    return trim($v, $trim);
                };
            }
            $result = array_map($trim, $result);
        }
        if ($skipEmpty) {
            // Wrapped with array_values to make array keys sequential after empty values removing
            $result = array_values(array_filter($result, function ($value) {
                return $value !== '';
            }));
        }
        return $result;
    }

    /**
     * Counts words in a string
     * @since 2.0.8
     *
     * @param string $string
     * @return int
     */
    public static function countWords($string)
    {
        return count(preg_split('/\s+/u', $string, null, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * Returns string represenation of number value with replaced commas to dots, if decimal point
     * of current locale is comma
     * @param int|float|string $value
     * @return string
     * @since 2.0.11
     */
    public static function normalizeNumber($value)
    {
        $value = "$value";

        $localeInfo = localeconv();
        $decimalSeparator = isset($localeInfo['decimal_point']) ? $localeInfo['decimal_point'] : null;

        if ($decimalSeparator !== null && $decimalSeparator !== '.') {
            $value = str_replace($decimalSeparator, '.', $value);
        }

        return $value;
    }

    /**
     * Возвращает позицию подстроки в строке.
     * @param string $needle Подстрока.
     * @param string $haystack Строка.
     * @param integer $offset Смещение.
     * @param bool $insensitive Нечювствительность к регистру.
     * @param bool $last Искать последнюю подстроку.
     * @param string|null $encoding Кодировка.
     * @return integer|false Позиция, с которой начинается первая или последняя найденная подстрока
     * или `false`, если подстрока не найдена.
     */
    public static function position($needle, $haystack, $offset = 0, $insensitive = false, $last = false, $encoding = null)
    {
        $encoding = Encoding::resolve($encoding);

        if ($encoding) {
            if ($insensitive) {
                return $last ?
                    mb_strripos($haystack, $needle, $offset, $encoding) :
                    mb_stripos($haystack, $needle, $offset, $encoding);
            } else {
                return $last ?
                    mb_strrpos($haystack, $needle, $offset, $encoding) :
                    mb_strpos($haystack, $needle, $offset, $encoding);
            }
        } else {
            if ($insensitive) {
                return $last ?
                    mb_strripos($haystack, $needle, $offset) :
                    mb_stripos($haystack, $needle, $offset);
            } else {
                return $last ?
                    mb_strrpos($haystack, $needle, $offset) :
                    mb_strpos($haystack, $needle, $offset);
            }
        }
    }

    /**
     * Метод для обрезания строк.
     * @param string $string Строка которую необходимо обрезать.
     * @param integer $offset Смещение.
     * @param integer|null $length Длина обрезания.
     * @param string|null $encoding Кодировка.
     * @return string Обрезанная строка.
     * @since 1.0.0
     */
    public static function cut($string, $offset, $length = null, $encoding = null)
    {
        $encoding = Encoding::resolve($encoding);

        return $encoding ?
            mb_strcut($string, $offset, $length, $encoding) :
            mb_strcut($string, $offset, $length);
    }

    /**
     * @param string $string1 Первая строка для сравнения.
     * @param string $string2 Вторая строка для сравнения.
     * @param integer $length Количество сравневаемых символов. Если 0, то сравнивает всю строку.
     * @param bool $insensitive Нечювствительность к регистру.
     * @param string|null $encoding Кодировка.
     * @return bool Результат сравнения.
     * @since 1.0.0
     */
    public static function compare($string1, $string2, $length = 0, $insensitive = false, $encoding = null)
    {
        $encoding = Encoding::resolve($encoding);

        if ($length > 1) {
            $string1 = static::cut($string1, 0, $length);
            $string2 = static::cut($string2, 0, $length);
        }

        return static::position(
            $string1,
            $string2,
            null,
            $insensitive,
            false,
            $encoding
        ) === 0;
    }

    /**
     * Конвертирует строку из одной кодировки в другую.
     * @param string $string Строка, которую необходимо конвертировать.
     * @param string $encodingTo Кодировка, в которую необходимо конвертировать.
     * @param null $encodingFrom Кодировка, из которой необходимо конвертировать.
     * @return string Конвертированная строка.
     */
    public static function convert($string, $encodingTo = null, $encodingFrom = null)
    {
        $string = Type::toString($string);
        return Encoding::convert($string, $encodingTo, $encodingFrom);
    }

    /**
     * Конвертирует всю строку в нижний регистр.
     * @param $string
     * @param string|null $encoding Кодировка. Если `null`, то берется кодировка приложения.
     * @return string Строка в нижнем регистре.
     * @since 1.0.0
     */
    public static function toLowerCase($string, $encoding = null)
    {
        $encoding = Encoding::resolve($encoding);

        return $encoding ?
            mb_strtolower($string, $encoding) :
            mb_strtolower($string);
    }

    /**
     * Конвертирует всю строку в верхний регистр.
     * @param $string
     * @param string|null $encoding Кодировка. Если `null`, то берется кодировка приложения.
     * @return string Строка в верхнем регистре.
     * @since 1.0.0
     */
    public static function toUpperCase($string, $encoding = null)
    {
        $encoding = Encoding::resolve($encoding);

        return $encoding ?
            mb_strtoupper($string, $encoding) :
            mb_strtoupper($string);
    }

    /**
     * Заменяет ключи массива rules на значения этих ключей в строке string.
     * @param string $string Строка, в которой необходимо произвести замену.
     * @param array $rules Правила замены.
     * Вид: [Что заменять => На что заменять]
     * @param null $count Количество замен.
     * @return string Строка, с замененными частями.
     */
    public static function replace($string, $rules, &$count = null) {
        $string = Type::toString($string);

        if (Type::isArrayable($rules)) {
            return str_replace(
                ArrayHelper::getKeys($rules),
                ArrayHelper::getValues($rules),
                $string,
                $count
            );
        }

        return $string;
    }

    /**
     * Заменяет макросы в строке.
     * @param string $string Строка, в которой необходимо заменить макросы.
     * @param array $rules Правила замены.
     * @param string $tagStart Тэг начала макроса.
     * @param string $tagEnd Тэг окончания макроса.
     * @param null $count Количество замен.
     * @return string Строка, с замененными макросами.
     */
    public static function replaceMacros($string, $rules, $tagStart = '#', $tagEnd = '#', &$count = null) {
        if (Type::isArrayable($rules)) {
            $macros = [];

            foreach ($rules as $key => $value)
                $macros[$tagStart.$key.$tagEnd] = $value;

            $string = static::replace($string, $macros, $count);
        }

        return $string;
    }

    /**
     * Возвращает часть строки.
     * @param string $string Строка, из которой необходимо взять часть.
     * @param integer $start Начальная позиция части.
     * @param integer|null $length Длина части.
     * @param string|null $encoding Кодировка. Если null, то определяется атоматически.
     * @return string
     */
    public static function slice($string, $start, $length = null, $encoding = null)
    {
        $string = Type::toString($string);
        $start = Type::toInteger($start);
        $length = $length === null ? null : Type::toInteger($length);
        $encoding = Encoding::resolve($encoding);

        if ($encoding) {
            return mb_substr($string, $start, $length, $encoding);
        }

        return mb_substr($string, $start, $length);
    }

    /**
     * Возвращает длину строки.
     * @param string $string
     * @param string|null $encoding
     * @return integer
     */
    public static function length($string, $encoding = null)
    {
        $string = Type::toString($string);
        $encoding = Encoding::resolve($encoding, Encoding::TYPE_DEFAULT, true);

        return mb_strlen($string, $encoding);
    }

    /**
     * Возвращает строку с большой буквы.
     * @param string $string
     * @param string $encoding
     * @return string
     */
    public static function toUpperCharacter($string, $encoding = null)
    {
        $length = static::length($string, $encoding);

        if ($length > 0) {
            $string = static::toUpperCase(static::slice($string, 0, 1), $encoding).static::slice($string, 1, $length - 1);
        }

        return $string;
    }

    /**
     * Возвращает строку с маленькой буквы.
     * @param string $string
     * @param string $encoding
     * @return string
     */
    public static function toLowerCharacter($string, $encoding = null)
    {
        $length = static::length($string, $encoding);

        if ($length > 0) {
            $string = static::toLowerCase(static::slice($string, 0, 1), $encoding).static::slice($string, 1, $length - 1);
        }

        return $string;
    }
}
