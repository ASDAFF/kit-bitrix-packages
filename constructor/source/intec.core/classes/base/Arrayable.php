<?php
namespace intec\core\base;

/**
 * Данный интрефейс указывает на то, что объект может быть превращен в массив.
 * Interface Arrayable
 * @package intec\core\base
 * @since 1.0.0
 */
interface Arrayable
{
    /**
     * Возвращает список полей или их объявлений.
     * @return array Список полей или их объявлений.
     * @see toArray()
     * @since 1.0.0
     */
    public function fields();

    /**
     * Возвращает список доп. полей или их объявлений,
     * которые будут добавлены к обычным полям в методе [[toArray]].
     * @return array Список доп. полей или их объявлений.
     * @see toArray()
     * @since 1.0.0
     */
    public function extraFields();

    /**
     * Метод преобразует объект в массив.
     * @param array $fields Поля, которые массив должен содержать.
     * Поля, которых нет в списке будут игнорированы.
     * Если массив пустой, то будут возвращены все поля.
     * @param array $expand Нужны ли дополнительные поля.
     * @param bool $recursive Преобразовывать вложенные объекты.
     * @return mixed Массив, содержащий итоговые поля объекта.
     * @since 1.0.0
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true);
}
