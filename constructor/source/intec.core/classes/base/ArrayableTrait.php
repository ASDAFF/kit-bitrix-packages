<?php
namespace intec\core\base;

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\web\Link;
use intec\core\web\Linkable;

/**
 * Трейт ArrayableTrait реализует интерфейс ArrayableInterface.
 * Class ArrayableTrait
 * @package intec\core\base
 * @since 1.0.0
 */
trait ArrayableTrait
{
    /**
     * Возвращает список полей или их объявлений.
     * @return array Список полей или их объявлений.
     * @see toArray()
     * @since 1.0.0
     */
    public function fields()
    {
        $fields = array_keys(Core::getObjectVars($this));
        return array_combine($fields, $fields);
    }

    /**
     * Возвращает список доп. полей или их объявлений,
     * которые будут добавлены к обычным полям в методе [[toArray]].
     * @return array Список доп. полей или их объявлений.
     * @see toArray()
     * @since 1.0.0
     */
    public function extraFields()
    {
        return [];
    }

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
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = [];
        foreach ($this->resolveFields($fields, $expand) as $field => $definition) {
            $data[$field] = is_string($definition) ? $this->$definition : call_user_func($definition, $this, $field);
        }

        if ($this instanceof Linkable) {
            $data['_links'] = Link::serialize($this->getLinks());
        }

        return $recursive ? ArrayHelper::toArray($data) : $data;
    }

    /**
     * Определяет какие поля будут возвращены методом [[toArray()]].
     * Этот метод проверяет запрошенные поля которые определены в [[fields()]] и [[extraFields()]]
     * для того, чтобы их вернуть.
     * @param array $fields Список запрошенных полей.
     * @param array $expand Список запрошенных доп. полей.
     * @return array Список полей, которые должны быть возвращены.
     */
    protected function resolveFields(array $fields, array $expand)
    {
        $result = [];

        foreach ($this->fields() as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }
            if (empty($fields) || in_array($field, $fields, true)) {
                $result[$field] = $definition;
            }
        }

        if (empty($expand)) {
            return $result;
        }

        foreach ($this->extraFields() as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }
            if (in_array($field, $expand, true)) {
                $result[$field] = $definition;
            }
        }

        return $result;
    }
}
