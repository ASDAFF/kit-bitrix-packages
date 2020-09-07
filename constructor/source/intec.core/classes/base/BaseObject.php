<?php
namespace intec\core\base;

use intec\Core;

/**
 * Class BaseObject
 * Базовый объект ядра.
 * @package intec\core\base
 * @since 1.0.0
 */
class BaseObject implements Configurable
{
    /**
     * Возвращает полное наименование этого класса, включая пространство имен.
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * Object конструктор.
     * @param array $config Параметры объекта.
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            Core::configure($this, $config);
        }
        $this->init();
    }

    /**
     * Инициализирует объект, вызывается после конструктора.
     */
    public function init()
    {
    }

    /**
     * Возвращает значение свойства объекта.
     * @param string $name Наименование свойства.
     * @return mixed Значение свойства.
     * @throws InvalidCallException Если свойство только для записи.
     * @throws UnknownPropertyException Неизвестное свойство.
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Устанавливает значение свойства объекта.
     * @param string $name Наименование свойства.
     * @param mixed $value Устанавливаемое значение.
     * @throws InvalidCallException Если свойство только для чтения.
     * @throws UnknownPropertyException Неизвестное свойство.
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Проверяет свойство на существование.
     * @param string $name Наименование свойства.
     * @return bool Свойство существует.
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else {
            return false;
        }
    }

    /**
     * Стирает значение свойства.
     * @param string $name Наименование свойства.
     * @throws InvalidCallException Если свойство только для чтения.
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Отображает ошибку при вызове несуществующего метода.
     * @param string $name Наименование метода.
     * @param array $params Параметры.
     * @throws UnknownMethodException Метода не существует.
     */
    public function __call($name, $params)
    {
        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
    }

    /**
     * Проверяет свойство на существование.
     * @param string $name Наименование свойства.
     * @param bool $checkVars Проверять переменные.
     * @return bool Свойство существует.
     */
    public function hasProperty($name, $checkVars = true)
    {
        return $this->canGetProperty($name, $checkVars) || $this->canSetProperty($name, false);
    }

    /**
     * Проверяет, можно-ли получить значение свойства.
     * @param string $name Наименование свойства.
     * @param bool $checkVars Проверять переменные.
     * @return bool Значение можно получить.
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Проверяет, можно-ли установить значение свойства.
     * @param string $name Наименование свойства.
     * @param bool $checkVars Проверять переменные.
     * @return bool Значение можно установить.
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Проверяет существование метода.
     * @param string $name Наименование метода.
     * @return bool Метод существует.
     */
    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}