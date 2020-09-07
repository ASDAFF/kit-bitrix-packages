<?php
namespace intec\template;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\base\Collection;
use intec\core\base\BaseObject;
use intec\core\helpers\Type;
use intec\constructor\models\Build;

class Properties extends BaseObject
{
    /**
     * @var Collection
     */
    public static $properties;

    /**
     * Возвращает коллекцию свойств.
     * @return Collection|null
     */
    public static function getCollection()
    {
        if (
            !Loader::includeModule('intec.constructor') &&
            !Loader::includeModule('intec.constructorlite')
        ) return null;

        if (static::$properties === null) {
            $build = Build::getCurrent();
            $page = $build->getPage();

            static::$properties = $page->getProperties();
        }

        return static::$properties;
    }

    /**
     * Возвращает значения параметров.
     * @param string|array $keys Ключ или ключи свойств.
     * @param boolean $collection Возвращать как коллекцию.
     * @return Collection|array|mixed|null
     */
    public static function get($keys, $collection = false)
    {
        if (empty($keys))
            return null;

        $properties = static::getCollection();

        if (empty($properties))
            return null;

        if (Type::isArrayable($keys))
            return $properties->getRange($keys, $collection, false);

        return $properties->get($keys);
    }

    /**
     * Устанавливает значения свойств.
     * @param array $values Массив с ключами и значениями свойств.
     */
    public static function set($values)
    {
        if (empty($values))
            return;

        $properties = static::getCollection();

        if (empty($properties))
            return;

        $properties->setRange($values);
    }
}