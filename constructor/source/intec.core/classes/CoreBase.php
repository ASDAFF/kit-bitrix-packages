<?php
namespace intec\core;

use intec\core\base\Application;
use intec\core\base\InvalidConfigException;
use intec\core\base\InvalidParamException;
use intec\core\base\UnknownClassException;
use intec\core\di\Container;
use intec\core\helpers\Encoding;
use intec\core\helpers\StringHelper;

/**
 * Главный класс ядра.
 * Class CoreBase
 * @package intec\core
 * @since 1.0.0
 */
class CoreBase
{
    /**
     * Состояние режима отладки.
     * @var bool
     * @since 1.0.0
     */
    protected static $debug = false;

    /**
     * Хранилище карты классов.
     * @var array
     * @since 1.0.0
     */
    public static $classes;

    /**
     * Экземпляр приложения.
     * @var Application
     * @since 1.0.0
     */
    public static $app;

    /**
     * Хранилище alias`ов.
     * @var array
     * @since 1.0.0
     */
    public static $aliases = ['@intec' => ['@intec/core' => __DIR__]];

    /**
     * Контайнер классов.
     * @var Container
     * @since 1.0.0
     */
    public static $container;

    /**
     * Возвращает текущую версию ядра.
     * @return string
     * @since 1.0.0
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Устанавливает состояние режима отладки.
     * @param boolean $state
     * @since 1.0.0
     */
    public static function setDebug($state)
    {
        static::$debug = boolval($state);
    }

    /**
     * Возвращает состояние режима отладки.
     * @return boolean Состояние режима отладки.
     * @since 1.0.0
     */
    public static function getDebug()
    {
        return static::$debug;
    }

    /**
     * Находит заданный Алиас.
     * @param string $alias Алиас.
     * @param boolean $throwException Вызывать-ли исключение.
     * @return boolean|string Путь или false.
     * @since 1.0.0
     */
    public static function getAlias($alias, $throwException = true)
    {
        if (strncmp($alias, '@', 1)) {
            return $alias;
        }

        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);

        if (isset(static::$aliases[$root])) {
            if (is_string(static::$aliases[$root])) {
                return $pos === false ? static::$aliases[$root] : static::$aliases[$root] . substr($alias, $pos);
            }

            foreach (static::$aliases[$root] as $name => $path) {
                if (strpos($alias . '/', $name . '/') === 0) {
                    return $path . substr($alias, strlen($name));
                }
            }
        }

        if ($throwException) {
            throw new InvalidParamException("Invalid path alias: $alias");
        }

        return false;
    }

    /**
     * Возвращает alias по исходному ключу.
     * @param string $alias Алиас.
     * @return boolean|string Алиас или false.
     * @since 1.0.0
     */
    public static function getRootAlias($alias)
    {
        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);

        if (isset(static::$aliases[$root])) {
            if (is_string(static::$aliases[$root])) {
                return $root;
            }

            foreach (static::$aliases[$root] as $name => $path) {
                if (strpos($alias . '/', $name . '/') === 0) {
                    return $name;
                }
            }
        }

        return false;
    }

    /**
     * Устанавливает alias.
     * @param string $alias Алиас.
     * @param string $path Путь.
     * @since 1.0.0
     */
    public static function setAlias($alias, $path)
    {
        if (strncmp($alias, '@', 1)) {
            $alias = '@' . $alias;
        }
        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);
        if ($path !== null) {
            $path = strncmp($path, '@', 1) ? rtrim($path, '\\/') : static::getAlias($path);
            if (!isset(static::$aliases[$root])) {
                if ($pos === false) {
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [$alias => $path];
                }
            } elseif (is_string(static::$aliases[$root])) {
                if ($pos === false) {
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [
                        $alias => $path,
                        $root => static::$aliases[$root],
                    ];
                }
            } else {
                static::$aliases[$root][$alias] = $path;
                krsort(static::$aliases[$root]);
            }
        } elseif (isset(static::$aliases[$root])) {
            if (is_array(static::$aliases[$root])) {
                unset(static::$aliases[$root][$alias]);
            } elseif ($pos === false) {
                unset(static::$aliases[$root]);
            }
        }
    }

    /**
     * Класс для автозагрузки.
     * @param string $class Полное наименование класса.
     * @throws UnknownClassException
     * @since 1.0.0
     */
    public static function autoload($class)
    {
        if (isset(static::$classes[$class])) {
            $file = static::$classes[$class];
            if ($file[0] === '@') {
                $file = static::getAlias($file);
            }
        } elseif (strpos($class, '\\') !== false) {
            $file = static::getAlias('@' . str_replace('\\', '/', $class) . '.php', false);
            if ($file === false || !is_file($file)) {
                return;
            }
        } else {
            return;
        }

        include($file);

        if (static::getDebug() && !class_exists($class, false) && !interface_exists($class, false) && !trait_exists($class, false)) {
            throw new UnknownClassException("Unable to find '$class' in file: $file. Namespace missing?");
        }
    }

    /**
     * Создает объект из полученных данных.
     * @param array|string $type Тип.
     * @param array $params Параметры.
     * @return object Объект.
     * @throws InvalidConfigException
     * @since 1.0.0
     */
    public static function createObject($type, array $params = [])
    {
        if (is_string($type)) {
            return static::$container->get($type, $params);
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            return static::$container->get($class, $params, $type);
        } elseif (is_callable($type, true)) {
            return static::$container->invoke($type, $params);
        } elseif (is_array($type)) {
            throw new InvalidConfigException('Object configuration must be an array containing a "class" element.');
        }

        throw new InvalidConfigException('Unsupported configuration type: ' . gettype($type));
    }

    /**
     * Устанавливает поля объекта из массива.
     * @param object $object Объект.
     * @param array $properties Массив с полями.
     * @return object Объект.
     * @since 1.0.0
     */
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }

    /**
     * Переводит сообщения в язык приложения.
     * Это короткий метод [[\intec\core\i18n\I18N::translate()]].
     *
     * @param string $category Категория.
     * @param string $message Сообщение для перевода.
     * @param array $params Параметры.
     * @param string|null $language Кодя зыка. Если `null`, то берется из языка приложения.
     * @return string Переведенное сообщение.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        if (static::$app !== null) {
            $message = static::$app->getI18n()->translate($category, $message, $params, $language ?: static::$app->language);
        } else {
            $placeholders = [];
            foreach ((array) $params as $name => $value) {
                $placeholders['{' . $name . '}'] = $value;
            }

            $message = ($placeholders === []) ? $message : strtr($message, $placeholders);
        }

        return $message;
    }

    /**
     * Возвращает список свойств объекта.
     * @param object $object Объект, свойства которого необходимо получить.
     * @return array Список свойств.
     */
    public static function getObjectVars($object)
    {
        return get_object_vars($object);
    }
}