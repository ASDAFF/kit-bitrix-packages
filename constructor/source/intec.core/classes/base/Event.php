<?php
namespace intec\core\base;

/**
 * Базовый класс для всех классов, поддерживающих события.
 * Class Event
 * @package intec\core\base
 * @since 1.0.0
 */
class Event extends BaseObject
{
    /**
     * Наименование события. Обработчики могут использовать
     * данное свойство для проверки обрабатываемого события.
     * @var string
     * @since 1.0.0
     */
    public $name;

    /**
     * Объект, который вызвал это событие.
     * Если не установлен, то будет установлен тот объект, который вызвал метод `trigger()`.
     * Так-же может иметь значение `null` если контекст вызова был статическим.
     * @var object
     * @since 1.0.0
     */
    public $sender;

    /**
     * Определяет, является ли событие обработанным. Когда установлено в `true`,
     * то обработка дальнейших событий прекращается.
     * @var bool
     * @since 1.0.0
     */
    public $handled = false;

    /**
     * Данные, которые приходят из [[Component::on()]] когда прикрепляется обработчик события.
     * @var mixed
     * @since 1.0.0
     */
    public $data;

    /**
     * Содержит все зарегистрированные глобальные события.
     * @var array
     * @since 1.0.0
     */
    private static $_events = [];


    /**
     * Прикрепляет обработчик событий к классовому событию.
     * @param string $class Полное наименование класса.
     * @param string $name Наменование события.
     * @param callable $handler Обработчик события.
     * @param mixed $data Данные для обработчика события.
     * @param bool $append Определяет куда добавлять новый обработчик события в список событий.
     * Если `true` то будет добавлен в конец списка. Если `false`, то будет добавлен в начало списка.
     * @see off()
     * @since 1.0.0
     */
    public static function on($class, $name, $handler, $data = null, $append = true)
    {
        $class = ltrim($class, '\\');
        if ($append || empty(self::$_events[$name][$class])) {
            self::$_events[$name][$class][] = [$handler, $data];
        } else {
            array_unshift(self::$_events[$name][$class], [$handler, $data]);
        }
    }

    /**
     * Открепляет обработчик событий от классового события.
     * @param string $class Полное наименование класса.
     * @param string $name Наменование события.
     * @param callable $handler Обработчик события.
     * Если `null`, все обработчики прикрепленные к указанному событию будут удалены.
     * @return bool Найден ли обработчик и откреплен.
     * @see on()
     * @since 1.0.0
     */
    public static function off($class, $name, $handler = null)
    {
        $class = ltrim($class, '\\');
        if (empty(self::$_events[$name][$class])) {
            return false;
        }
        if ($handler === null) {
            unset(self::$_events[$name][$class]);
            return true;
        }

        $removed = false;
        foreach (self::$_events[$name][$class] as $i => $event) {
            if ($event[0] === $handler) {
                unset(self::$_events[$name][$class][$i]);
                $removed = true;
            }
        }
        if ($removed) {
            self::$_events[$name][$class] = array_values(self::$_events[$name][$class]);
        }
        return $removed;
    }

    /**
     * Удаляет все зарегистрированные события.
     * @see on()
     * @see off()
     * @since 1.0.0
     */
    public static function offAll()
    {
        self::$_events = [];
    }

    /**
     * Возвращает значение, которое показывает прикреплены-ли какие нибудь обработчики к классовому событию.
     * @param string|object $class Объект или полное наименование класса.
     * @param string $name Наименование события.
     * @return bool Прикреплен-ли хоть 1 обработчик.
     * @since 1.0.0
     */
    public static function hasHandlers($class, $name)
    {
        if (empty(self::$_events[$name])) {
            return false;
        }
        if (is_object($class)) {
            $class = get_class($class);
        } else {
            $class = ltrim($class, '\\');
        }

        $classes = array_merge(
            [$class],
            class_parents($class, true),
            class_implements($class, true)
        );

        foreach ($classes as $class) {
            if (!empty(self::$_events[$name][$class])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Вызывает классовое событие.
     * @param string|object $class Объект или полное наименование класса.
     * @param string $name Наименование события.
     * @param Event $event Экземпляр класса Event. Если не установлен, то по умолчанию будет создан новый экземпляр класса [[Event]].
     * @since 1.0.0
     */
    public static function trigger($class, $name, $event = null)
    {
        if (empty(self::$_events[$name])) {
            return;
        }
        if ($event === null) {
            $event = new static;
        }
        $event->handled = false;
        $event->name = $name;

        if (is_object($class)) {
            if ($event->sender === null) {
                $event->sender = $class;
            }
            $class = get_class($class);
        } else {
            $class = ltrim($class, '\\');
        }

        $classes = array_merge(
            [$class],
            class_parents($class, true),
            class_implements($class, true)
        );

        foreach ($classes as $class) {
            if (!empty(self::$_events[$name][$class])) {
                foreach (self::$_events[$name][$class] as $handler) {
                    $event->data = $handler[1];
                    call_user_func($handler[0], $event);
                    if ($event->handled) {
                        return;
                    }
                }
            }
        }
    }
}
