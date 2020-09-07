<?php
namespace intec\constructor\structure;

use intec\Core;
use intec\constructor\base\ScannableSnippets;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

/**
 * Class Widgets
 * @package intec\constructor\models\template
 */
class Widgets extends ScannableSnippets
{
    /**
     * @inheritdoc
     */
    protected static $cache;

    /**
     * @inheritdoc
     */
    protected static function getPath($namespace = null, $id = null)
    {
        $path = Core::getAlias('@widgets');

        if ($namespace !== null) {
            $path .= '/'.$namespace;

            if ($id !== null)
                $path .= '/'.$id.'/Widget.php';
        }

        return $path;
    }

    /**
     * @inheritdoc
     */
    protected static function getNamespace($namespace, $id)
    {
        return 'widgets\\'.$namespace.'\\'.$id.'\\Widget';
    }

    /**
     * Устанавливает виджет в систему.
     * @param Path|string $directory
     * @param string $namespace Пространство имен виджета.
     * @param string $id Идентификатор виджета.
     * @param boolean $replace Заменить, если существует.
     * @return boolean
     */
    public static function install($directory, $namespace, $id, $replace = false)
    {
        $directory = Path::from($directory);

        if (empty($namespace))
            return false;

        if (empty($id))
            return false;

        if (!FileHelper::isDirectory($directory->getValue()))
            return false;

        $path = static::getPath($namespace, $id);
        $path = Path::from($path)->getParent();

        if (FileHelper::isDirectory($path->value) && !$replace)
            return false;

        if ($replace)
            FileHelper::removeDirectory($path->value);

        FileHelper::copyDirectory(
            $directory->value,
            $path->value
        );

        static::$cache = null;

        return true;
    }

    /**
     * Удаляет виджет из системы.
     * @param string $namespace Пространство имен виджета.
     * @param string $id Идентификатор виджета.
     * @return boolean
     */
    public static function uninstall($namespace, $id)
    {
        if (empty($namespace))
            return false;

        if (empty($id))
            return false;

        $path = static::getPath($namespace, $id);
        $path = Path::from($path)->getParent();

        FileHelper::removeDirectory($path->value);

        return true;
    }
}