<?php
namespace intec\core\base;

trait EventsTrait
{
    /**
     * Список обработчиков событий компонента.
     * Вид: [Событие => [Обработчики события]]
     * @var array
     * @since 1.0.0
     */
    protected $_events = [];

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
     * @return bool Событие не обработано.
     * @since 1.0.0
     */
    public function trigger($name, Event $event = null)
    {
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
                    return false;
                }
            }
        }
        // invoke class-level attached handlers
        Event::trigger($this, $name, $event);
        return true;
    }
}