<?php
namespace intec\core\di;

use intec\Core;
use Closure;
use intec\core\base\Component;
use intec\core\base\InvalidConfigException;

/**
 * Общий класс для хранения компонентов.
 * Class ServiceLocator
 * @package intec\core\di
 * @since 1.0.0
 */
class ServiceLocator extends Component
{
    /**
     * @var array Список компонентов проиндексированных по их индексам.
     * @since 1.0.0
     */
    private $_components = [];
    /**
     * @var array Список определений компонентов проиндексированных по их индексам.
     * @since 1.0.0
     */
    private $_definitions = [];


    /**
     * Метод для получения компонента.
     * @param string $name component or property name
     * @return mixed the named property value
     * @since 1.0.0
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        } else {
            return parent::__get($name);
        }
    }

    /**
     * Метод проверки существования компонента.
     * @param string $name the property name or the event name
     * @return bool whether the property value is null
     * @since 1.0.0
     */
    public function __isset($name)
    {
        if ($this->has($name)) {
            return true;
        } else {
            return parent::__isset($name);
        }
    }

    /**
     * Проверяет существование компонента или объявления компонента.
     * @param string $id Индентификатор компонента.
     * @param bool $checkInstance Проверять компонент что он инициализирован.
     * @return bool Компонент доступен или проинициализирован.
     * @see set()
     * @since 1.0.0
     */
    public function has($id, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_components[$id]) : isset($this->_definitions[$id]);
    }

    /**
     * Возвращает компонент по идентификатору.
     * @param string $id Идентификатор.
     * @param bool $throwException Вызывать ли исключение если компонент не найден.
     * @return object|null Компонент.
     * @throws InvalidConfigException Если идентификатор указывает на несуществующий компонент.
     * @see has()
     * @see set()
     * @since 1.0.0
     */
    public function get($id, $throwException = true)
    {
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                return $this->_components[$id] = $definition;
            } else {
                return $this->_components[$id] = Core::createObject($definition);
            }
        } elseif ($throwException) {
            throw new InvalidConfigException("Unknown component ID: $id");
        } else {
            return null;
        }
    }

    /**
     * Регистрирует компонент.
     * Если компонент уже существует, то он будет перезаписан.
     * @param string $id Идентификатор.
     * @param mixed $definition Объявление компонента.
     * @throws InvalidConfigException При неверном объявлении.
     * @since 1.0.0
     */
    public function set($id, $definition)
    {
        if ($definition === null) {
            unset($this->_components[$id], $this->_definitions[$id]);
            return;
        }

        unset($this->_components[$id]);

        if (is_object($definition) || is_callable($definition, true)) {
            // an object, a class name, or a PHP callable
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            // a configuration array
            if (isset($definition['class'])) {
                $this->_definitions[$id] = $definition;
            } else {
                throw new InvalidConfigException("The configuration for the \"$id\" component must contain a \"class\" element.");
            }
        } else {
            throw new InvalidConfigException("Unexpected configuration type for the \"$id\" component: " . gettype($definition));
        }
    }

    /**
     * Удаляет компонент.
     * @param string $id Идентификатор.
     * @since 1.0.0
     */
    public function clear($id)
    {
        unset($this->_definitions[$id], $this->_components[$id]);
    }

    /**
     * Возвращает список объявленных или инициализированных компонентов.
     * @param bool $returnDefinitions Возвращать объявления компонентов или инициализированные компоненты.
     * @return array Список компонентов или объявлений.
     * @since 1.0.0
     */
    public function getComponents($returnDefinitions = true)
    {
        return $returnDefinitions ? $this->_definitions : $this->_components;
    }

    /**
     * Регистрирует несколько компонентов за раз.
     * @param array $components Объявления компонентов или инициализированные компоненты.
     * @since 1.0.0
     */
    public function setComponents($components)
    {
        foreach ($components as $id => $component) {
            $this->set($id, $component);
        }
    }
}
