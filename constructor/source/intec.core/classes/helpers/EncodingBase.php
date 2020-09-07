<?php
namespace intec\core\helpers;

use intec\Core;

/**
 * Базовый класс кодировок.
 * Class EncodingBase
 * @package intec\core\helpers
 * @since 1.0.0
 * @author apocalypsisdimon@gmail.com
 */
class EncodingBase
{
    const TYPE_DEFAULT = null;
    const TYPE_DATABASE = 'database';

    const Bit7 = '7bit';
    const Bit8 = '8bit';
    const KOI8R = 'KOI8-R';
    const KOI8U = 'KOI8-U';
    const UTF7 = 'UTF-7';
    const UTF7IMAP = 'UTF7-IMAP';
    const UTF8 = 'UTF-8';
    const UTF16 = 'UTF-16';
    const UTF16BE = 'UTF-16BE';
    const UTF16LE = 'UTF-16LE';
    const UTF32 = 'UTF-32';
    const UTF32BE = 'UTF-32BE';
    const UTF32LE = 'UTF-32LE';
    const Windows1251 = 'Windows-1251';
    const Windows1252 = 'Windows-1252';
    const CP866 = 'CP866';

    /**
     * Список алиасов для кодировок, где ключом является кодировка,
     * а значением - массив алиасов для нее.
     * @return array
     */
    protected static function getAliases()
    {
        return [
            static::KOI8R => [
                static::TYPE_DATABASE => 'KOI8R'
            ],
            static::KOI8U => [
                static::TYPE_DATABASE => 'KOI8U'
            ],
            static::UTF7 => ['UTF7'],
            static::UTF7IMAP => ['UTF7IMAP'],
            static::UTF8 => [
                static::TYPE_DATABASE => 'UTF8'
            ],
            static::UTF16 => [
                static::TYPE_DATABASE => 'UTF16'
            ],
            static::UTF16BE => ['UTF16BE'],
            static::UTF16LE => ['UTF16LE'],
            static::UTF32 => [
                static::TYPE_DATABASE => 'UTF32'
            ],
            static::UTF32BE => ['UTF32BE'],
            static::UTF32LE => ['UTF32LE'],
            static::Windows1251 => [
                static::TYPE_DATABASE => 'CP1251'
            ],
            static::Windows1252 => [
                'CP1252',
                static::TYPE_DATABASE => 'latin1'
            ],
            static::CP866 => ['IBM866']
        ];
    }

    /**
     * Список кодировок.
     * @return array
     */
    public static function getEncodings()
    {
        return [
            static::Bit7,
            static::Bit8,
            static::KOI8R,
            static::KOI8U,
            static::UTF7,
            static::UTF7IMAP,
            static::UTF8,
            static::UTF16,
            static::UTF16BE,
            static::UTF16LE,
            static::UTF32,
            static::UTF32BE,
            static::UTF32LE,
            static::Windows1251,
            static::Windows1252,
            static::CP866
        ];
    }

    /**
     * Возвращает кодировку по умолчанию.
     * @return string
     */
    public static function getDefault()
    {
        return Core::$app ? Core::$app->charset : static::UTF8;
    }

    /**
     * Разрешает кодировку. Пытается найти кодировку по ее псевдониму.
     * @param string $encoding Псевдоним кодировки.
     * @param string $type Тип кодировки.
     * @param bool $default Возвращать кодировку по умолчанию если параметр $encoding пуст.
     * @return string|null
     */
    public static function resolve($encoding, $type = self::TYPE_DEFAULT, $default = false)
    {
        if (!$encoding)
            if ($default) {
                return static::getDefault();
            } else {
                return null;
            }

        $encoding = Type::toString($encoding);
        $encodings = static::getEncodings();
        $aliases = static::getAliases();

        foreach ($encodings as $key) {
            if (StringHelper::compare($key, $encoding, 0, true)) {
                break;
            }

            $key = null;
        }

        if (empty($key))
            foreach ($aliases as $key => $value) {
                $exit = false;

                foreach ($value as $alias) {
                    if (StringHelper::compare($alias, $encoding, 0, true)) {
                        $exit = true;
                        break;
                    }
                }

                if ($exit)
                    break;
            }

        unset($exit);

        if (!empty($key)) {
            if ($type == static::TYPE_DEFAULT) {
                return $key;
            } else {
                $value = ArrayHelper::getValue($aliases, [$key, $type]);

                if (!empty($value)) {
                    return $value;
                }

                return $key;
            }
        }

        return null;
    }

    public static function convert($object, $encodingTo = null, $encodingFrom = null)
    {
        $encodingTo = self::resolve($encodingTo, Encoding::TYPE_DEFAULT, true);
        $encodingFrom = self::resolve($encodingFrom);

        if (empty($encodingTo))
            return $object;

        if ($encodingTo === $encodingFrom)
            return $object;

        $convert = function ($object) use (&$convert, &$encodingTo, &$encodingFrom) {
            $result = null;

            if (Type::isArrayable($object) || ArrayHelper::isTraversable($object)) {
                $result = [];

                foreach ($object as $key => $value) {
                    $key = $convert($key);
                    $result[$key] = $convert($value);
                }
            } else if (Type::isObject($object)) {
                $result = $object;

                foreach ($result as $key => $value) {
                    $key = $convert($key);
                    $result->{$key} = $convert($value);
                }
            } else if (Type::isString($object)) {
                $result = !empty($encodingFrom) ?
                    mb_convert_encoding($object, $encodingTo, $encodingFrom) :
                    mb_convert_encoding($object, $encodingTo);
            } else {
                $result = $object;
            }

            return $result;
        };

        return $convert($object);
    }
}