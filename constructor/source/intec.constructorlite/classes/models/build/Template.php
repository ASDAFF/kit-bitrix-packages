<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\core\base\Collection;
use intec\core\base\BaseObject;
use intec\constructor\models\Build;

/**
 * Class Template
 * @package intec\constructor\models\build
 */
class Template extends BaseObject
{
    /**
     * Коллекция свойств.
     * @var Collection
     */
    protected $_properties;

    /**
     * Возвращает сборку к которой принадлежит шаблон.
     * @param bool $result
     * @return Build|null
     */
    public function getBuild($result = false)
    {
        return Build::getCurrent();
    }

    /**
     * Возвращает значение свойства по коду.
     * @param bool $theme Использовать тему.
     * - true - Брать тему шаблона.
     * - false - Не использовать.
     * - Theme - Указать тему.
     * @param bool $reset Получить настройки из базы заного.
     * @return Collection
     */
    public function getPropertiesValues($theme = true, $reset = false)
    {
        if ($this->_properties === null || $reset)
            $this->_properties = new Collection();

        return $this->_properties;
    }
}