<?php
namespace intec\core\base;

/**
 * Базовый класс для всех классов-поведений.
 * Поведения используются для модификации компонента без редактирования его кода.
 * Class Behavior
 * @package intec\core\base
 * @since 1.0.0
 */
class Behavior extends BaseObject
{
    /**
     * Компонент, в котором используется данное поведение.
     * @var Component
     * @since 1.0.0
     */
    public $owner;

    /**
     * События компонента, при которых срабатывает поведение.
     * @return array Список событий сопоставленных методам.
     * Вид: [Событие => Метод класса]
     * @since 1.0.0
     */
    public function events()
    {
        return [];
    }

    /**
     * Прикрепляет поведение к компоненту.
     * @param Component $owner Компонент, к которому необходимо прикрепить событие.
     * @since 1.0.0
     */
    public function attach($owner)
    {
        $this->owner = $owner;
        foreach ($this->events() as $event => $handler) {
            $owner->on($event, is_string($handler) ? [$this, $handler] : $handler);
        }
    }

    /**
     * Открепляет поведение от компонента.
     * @since 1.0.0
     */
    public function detach()
    {
        if ($this->owner) {
            foreach ($this->events() as $event => $handler) {
                $this->owner->off($event, is_string($handler) ? [$this, $handler] : $handler);
            }
            $this->owner = null;
        }
    }
}
