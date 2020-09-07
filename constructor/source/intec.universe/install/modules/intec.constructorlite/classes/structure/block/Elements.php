<?php
namespace intec\constructor\structure\block;

use intec\Core;
use intec\constructor\base\ScannableSnippets;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

/**
 * Class Elements
 * @package intec\constructor\structure\block
 */
class Elements extends ScannableSnippets
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
        $path = Core::getAlias('@elements');

        if ($namespace !== null) {
            $path .= '/'.$namespace;

            if ($id !== null)
                $path .= '/'.$id.'/Element.php';
        }

        return $path;
    }

    /**
     * @inheritdoc
     */
    protected static function getNamespace($namespace, $id)
    {
        return 'elements\\'.$namespace.'\\'.$id.'\\Element';
    }

    /**
     * Устанавливает элемент в систему.
     * @param Path|string $directory
     * @param string $namespace Пространство имен элемента.
     * @param string $id Идентификатор элемента.
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
     * Удаляет элемент из системы.
     * @param string $namespace Пространство имен элемента.
     * @param string $id Идентификатор элемента.
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