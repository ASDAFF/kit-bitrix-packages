<?php
namespace intec\core\base;

use intec\Core;

/**
 * Компонент, это базовый класс, который реализует функционал свойств,
 * событий и поведений.
 * Class Component
 * @package intec\core\base
 * @since 1.0.0
 */
class Component extends BaseObject
{
    /**
     * Список обработчиков событий компонента.
     * Вид: [Событие => [Обработчики события]]
     * @var array
     * @since 1.0.0
     */
    private $_events = [];
    /**
     * Список прикрепленных поведений компонента.
     * Вид: [Наименование поведения => Поведение]
     * @var Behavior[]|null
     * @since 1.0.0
     */
    private $_behaviors;

    /**
     * Возвращает значение свойства компонента.
     * Данный метод ищет свойства в следующем порядке:
     *
     *  - Метод типа get{Наименование}
     *  - Свойство поведеня
     *
     * @param string $name Наименование свойства.
     * @return mixed Значение компонента или поведения компонента.
     * @throws UnknownPropertyException Если свойство не объявлено.
     * @throws InvalidCallException Если свойство доступно только для записи.
     * @see __set()
     * @since 1.0.0
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canGetProperty($name)) {
                return $behavior->$name;
            }
        }

        if (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Устанавливает значение свойства компонента.
     * Данный метод устанавливает значение в следующем порядке:
     *
     *  - Свойство типа set{Наименование}
     *  - Событие если наименование 'on {Событие}'
     *  - Поведение если наименование 'as {Наименование поведения}'
     *  - Свойство поведения
     *
     * @param string $name Наименование свойства.
     * @param mixed $value Значение свойства.
     * @throws UnknownPropertyException Если свойство не объявлено.
     * @throws InvalidCallException Если свойство только для чтения.
     * @see __get()
     * @since 1.0.0
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);

            return;
        } elseif (strncmp($name, 'on ', 3) === 0) {
            // on event: attach event handler
            $this->on(trim(substr($name, 3)), $value);

            return;
        } elseif (strncmp($name, 'as ', 3) === 0) {
            // as behavior: attach behavior
            $name = trim(substr($name, 3));
            $this->attachBehavior($name, $value instanceof Behavior ? $value : Core::createObject($value));

            return;
        }

        // behavior property
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canSetProperty($name)) {
                $behavior->$name = $value;
                return;
            }
        }

        if (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Проверяет свойство на существование.
     * Данный метод проверяет свойства в следующем порядке:
     *
     *  - Метод типа get{Наименование}
     *  - Свойство поведеня
     *  - Возвращает `false` если свойство не найдено.
     *
     * @param string $name Наименование свойства.
     * @return bool Найдено ли свойство.
     * @since 1.0.0
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }

        // behavior property
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canGetProperty($name)) {
                return $behavior->$name !== null;
            }
        }

        return false;
    }

    /**
     * Устанавливает значение свойству как равным `null`.
     * Данный метод проверяет свойства в следующем порядке:
     *
     *  - Метод типа set{Наименование}
     *  - Свойство поведения
     *
     * @param string $name Наименование свойства.
     * @throws InvalidCallException Если свойство только для чтения.
     * @since 1.0.0
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
            return;
        }

        // behavior property
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canSetProperty($name)) {
                $behavior->$name = null;
                return;
            }
        }

        throw new InvalidCallException('Unsetting an unknown or read-only property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Вызывает метод, который находится вне данного класса.
     * Метод будет искаться в поведениях данного класса.
     *
     * @param string $name Наименование метода.
     * @param array $params Параметры метода.
     * @return mixed Возвращаемое значение.
     * @throws UnknownMethodException Если метод не найден.
     * @since 1.0.0
     */
    public function __call($name, $params)
    {
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $object) {
            if ($object->hasMethod($name)) {
                return call_user_func_array([$object, $name], $params);
            }
        }
        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
    }

    /**
     * Срабатывает после клонирования данного объекта.
     * Удаляет поведения и обработчики событий данного объекта.
     */
    public function __clone()
    {
        $this->_events = [];
        $this->_behaviors = null;
    }

    /**
     * Проверяет, существует ли свойство в объекте.
     * Свойство найдено если:
     *
     * - Существуют метод get{Наименование} или set{Наименование}
     * - Если существует само свойство (при `$checkVars` равной `true`);
     * - Если свойство существует в поведениях (при `$checkBehaviors` равной `true`).
     *
     * @param string $name Наименование свойства.
     * @param bool $checkVars Проверять свойства.
     * @param bool $checkBehaviors Проверять поведения.
     * @return bool Объект имеет свойство.
     * @see canGetProperty()
     * @see canSetProperty()
     * @since 1.0.0
     */
    public function hasProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        return $this->canGetProperty($name, $checkVars, $checkBehaviors) || $this->canSetProperty($name, false, $checkBehaviors);
    }

    /**
     * Проверяет, можно ли получить значение свойства.
     * Значение можно получить если:
     *
     * - Существуют метод get{Наименование}
     * - Если существует само свойство (при `$checkVars` равной `true`);
     * - Если значение можно получить из поведений (при `$checkBehaviors` равной `true`).
     *
     * @param string $name Наименование свойства.
     * @param bool $checkVars Проверять свойства.
     * @param bool $checkBehaviors Проверять поведения.
     * @return bool Значение можно получить.
     * @since 1.0.0
     */
    public function canGetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name)) {
            return true;
        } elseif ($checkBehaviors) {
            $this->ensureBehaviors();
            foreach ($this->_behaviors as $behavior) {
                if ($behavior->canGetProperty($name, $checkVars)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Проверяет, можно ли установить значение свойства.
     * Значение можно установить если:
     *
     * - Существуют метод set{Наименование}
     * - Если существует само свойство (при `$checkVars` равной `true`);
     * - Если значение можно установить в поведения (при `$checkBehaviors` равной `true`).
     *
     * @param string $name Наименование свойства.
     * @param bool $checkVars Проверять свойства.
     * @param bool $checkBehaviors Проверять поведения.
     * @return bool Значение можно установить.
     * @since 1.0.0
     */
    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name)) {
            return true;
        } elseif ($checkBehaviors) {
            $this->ensureBehaviors();
            foreach ($this->_behaviors as $behavior) {
                if ($behavior->canSetProperty($name, $checkVars)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Проверяет, существует ли метод в объекте.
     * Метод найден если:
     *
     * - Если существует само метод;
     * - Если метод существует в поведениях (при `$checkBehaviors` равной `true`).
     *
     * @param string $name Наименование свойства.
     * @param bool $checkBehaviors Проверять поведения.
     * @return bool Объект имеет метод.
     * @since 1.0.0
     */
    public function hasMethod($name, $checkBehaviors = true)
    {
        if (method_exists($this, $name)) {
            return true;
        } elseif ($checkBehaviors) {
            $this->ensureBehaviors();
            foreach ($this->_behaviors as $behavior) {
                if ($behavior->hasMethod($name)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Список объявлений поведений компонента.
     * @return array
     * @since 1.0.0
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * Проверяет, есть ли обработчики у конкретного события.
     * @param string $name Наименование события.
     * @return bool У события есть обработчики.
     */
    public function hasEventHandlers($name)
    {
        $this->ensureBehaviors();
        return !empty($this->_events[$name]) || Event::hasHandlers($this, $name);
    }

    /**
     * Привязывает обработчик к событию.
     * @param string $name Наименование события.
     * @param callable $handler Обработчик события.
     * @param mixed $data Данные для обработчика.
     * @param bool $append Добалять в конец списка.
     * @since 1.0.0
     */
    public function on($name, $handler, $data = null, $append = true)
    {
        $this->ensureBehaviors();
        if ($append || empty($this->_events[$name])) {
            $this->_events[$name][] = [$handler, $data];
        } else {
            array_unshift($this->_events[$name], [$handler, $data]);
        }
    }

    /**
     * Отвязывает обработчик от события.
     * Если `$handler` равен `null`, то удаляются все события.
     * @param string $name Наименование события.
     * @param callable|null $handler Обработчик события.
     * @return bool Обработчик отвязан.
     */
    public function off($name, $handler = null)
    {
        $this->ensureBehaviors();
        if (empty($this->_events[$name])) {
            return false;
        }
        if ($handler === null) {
            unset($this->_events[$name]);
            return true;
        }

        $removed = false;
        foreach ($this->_events[$name] as $i => $event) {
            if ($event[0] === $handler) {
                unset($this->_events[$name][$i]);
                $removed = true;
            }
        }
        if ($removed) {
            $this->_events[$name] = array_values($this->_events[$name]);
        }
        return $removed;
    }

    /**
     * Вызывает событие. Приводит к выполнению всех обработчиков указанного события.
     * @param string $name Наименование события.
     * @param Event|null $event Объект события. Если `null` то создастся стандартный.
     * @since 1.0.0
     */
    public function trigger($name, Event $event = null)
    {
        $this->ensureBehaviors();
        if (!empty($this->_events[$name])) {
            if ($event === null) {
                $event = new Event;
            }
            if ($event->sender === null) {
                $event->sender = $this;
            }
            $event->handled = false;
            $event->name = $name;
            foreach ($this->_events[$name] as $handler) {
                $event->data = $handler[1];
                call_user_func($handler[0], $event);
                // stop further handling if the event is handled
                if ($event->handled) {
                    return;
                }
            }
        }
        // invoke class-level attached handlers
        Event::trigger($this, $name, $event);
    }

    /**
     * Возвращает именованное поведение.
     * @param string $name Наименование поведения.
     * @return null|Behavior Поведение или `null`, если не существует.
     * @since 1.0.0
     */
    public function getBehavior($name)
    {
        $this->ensureBehaviors();
        return isset($this->_behaviors[$name]) ? $this->_behaviors[$name] : null;
    }

    /**
     * Возвращает все прикрепленные к компоненту поведения.
     * @return Behavior[] Список поведений.
     * @since 1.0.0
     */
    public function getBehaviors()
    {
        $this->ensureBehaviors();
        return $this->_behaviors;
    }

    /**
     * Прикрепляет поведение к данному компоненту.
     * @param string $name Наименование поведения.
     * @param Behavior|string|array $behavior Объект [[Behavior]] или его объявление.
     * @return Behavior Поведение.
     * @since 1.0.0
     */
    public function attachBehavior($name, $behavior)
    {
        $this->ensureBehaviors();
        return $this->attachBehaviorInternal($name, $behavior);
    }

    /**
     * @param array $behaviors Список поведений.
     * Вид: [Наименование поведения => Объект [[Behavior]] или его объявление]
     * @since 1.0.0
     */
    public function attachBehaviors($behaviors)
    {
        $this->ensureBehaviors();
        foreach ($behaviors as $name => $behavior) {
            $this->attachBehaviorInternal($name, $behavior);
        }
    }

    /**
     * Отвязывает поведение от компонента.
     * @param string $name Наименование поведения.
     * @return Behavior|null Поведение или `null`
     * @since 1.0.0
     */
    public function detachBehavior($name)
    {
        $this->ensureBehaviors();
        if (isset($this->_behaviors[$name])) {
            $behavior = $this->_behaviors[$name];
            unset($this->_behaviors[$name]);
            $behavior->detach();
            return $behavior;
        }

        return null;
    }

    /**
     * Отвязывает все поведения от данного компонента.
     * @since 1.0.0
     */
    public function detachBehaviors()
    {
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $name => $behavior) {
            $this->detachBehavior($name);
        }
    }

    /**
     * Проверяет, инициализированы ли поведения.
     * Если нет, то инициализирует их.
     */
    public function ensureBehaviors()
    {
        if ($this->_behaviors === null) {
            $this->_behaviors = [];
            foreach ($this->behaviors() as $name => $behavior) {
                $this->attachBehaviorInternal($name, $behavior);
            }
        }
    }

    /**
     * Общий метод, который используется для привязки поведений.
     * @param string $name Наименование поведения.
     * @param Behavior|string|array $behavior Поведение или его объявление.
     * @return Behavior Прикрепленное поведение.
     */
    private function attachBehaviorInternal($name, $behavior)
    {
        if (!($behavior instanceof Behavior)) {
            $behavior = Core::createObject($behavior);
        }
        if (is_int($name)) {
            $behavior->attach($this);
            $this->_behaviors[] = $behavior;
        } else {
            if (isset($this->_behaviors[$name])) {
                $this->_behaviors[$name]->detach();
            }
            $behavior->attach($this);
            $this->_behaviors[$name] = $behavior;
        }

        return $behavior;
    }
}
