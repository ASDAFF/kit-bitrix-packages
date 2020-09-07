<?php
namespace intec\constructor\base;

use intec\Core;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\StringHelper;
use intec\core\io\Path;
use ReflectionClass;
use ReflectionException;

/**
 * Коллекция снипетов.
 * Class Snippets
 * @package intec\constructor\base
 */
abstract class ScannableSnippets extends Snippets
{
    /**
     * Кэшированные снипеты.
     * @var ScannableSnippets|ScannableSnippet[]
     */
    protected static $cache;

    /**
     * Директория снипетов.
     * @param string|null $namespace
     * @param string|null $id
     * @return string
     */
    protected static function getPath($namespace = null, $id = null)
    {
        return null;
    }

    /**
     * Пространство имен снипетов.
     * @param string $namespace
     * @param string $id
     * @return string
     */
    protected static function getNamespace($namespace, $id)
    {
        return null;
    }

    /**
     * Возвращает список всех снипетов.
     * @param bool $collection
     * @param bool $refresh
     * @return ScannableSnippets|ScannableSnippet[]
     */
    public static function all($collection = true, $refresh = false)
    {
        if (static::$cache === null || $refresh) {
            $result = [];
            $namespaces = FileHelper::getDirectoryEntries(
                static::getPath(),
                false
            );

            foreach ($namespaces as $namespace) {
                $ids = FileHelper::getDirectoryEntries(
                    static::getPath($namespace),
                    false
                );

                foreach ($ids as $id) {
                    /** @var Snippet $instance */
                    $instance = static::create($namespace.':'.$id);

                    if (empty($instance))
                        continue;

                    $result[$instance->getCode()] = $instance;
                }
            }

            static::$cache = $result;
        }

        if ($collection) {
            return new static(static::$cache);
        }

        return static::$cache;
    }

    /**
     * Создает снипет по коду.
     * @param $code
     * @return ScannableSnippet|null
     */
    public static function create($code)
    {
        $code = explode(':', $code);
        $namespace = ArrayHelper::getValue($code, 0);
        $id = ArrayHelper::getValue($code, 1);

        if (empty($id) || empty($namespace))
            return null;

        $path = static::getPath($namespace, $id);
        $class = static::getNamespace($namespace, $id);
        $class = StringHelper::replace($class, [
            '.' => '\\'
        ]);

        if (!FileHelper::isFile($path))
            return null;

        Core::$classes[$class] = $path;

        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $exception) {
            $reflection = null;
        }

        if (empty($reflection))
            return null;

        if (!$reflection->isInstantiable())
            return null;

        /** @var ScannableSnippet $instance */
        $instance = $reflection->newInstance();

        if (!static::validate($instance))
            return null;

        return $instance;
    }

    /**
     * Проверка элемента.
     * @param ScannableSnippet $item
     * @return bool
     */
    protected static function validate($item)
    {
        return $item instanceof ScannableSnippet;
    }

    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return static::validate($item);
    }
}