<?php
namespace intec\core\collections;

use CDBResult;
use Closure;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class Arrays extends Collection
{
    /**
     * Преобразует CDBResult в коллекцию.
     * @param CDBResult $result
     * @param bool $handle
     * @param Closure|null $callback
     * @return Arrays
     * @since 1.0.0
     */
    public static function fromDBResult($result, $handle = false, $callback = null)
    {
        $method = $handle ? 'GetNext' : 'Fetch';
        $items = [];

        if ($callback instanceof Closure) {
            while ($item = $result->$method()) {
                $return = $callback($item);

                if (!Type::isArrayable($return))
                    continue;

                if (ArrayHelper::getValue($return, 'skip'))
                    continue;

                $key = ArrayHelper::getValue($return, 'key');
                $value = ArrayHelper::getValue($return, 'value');

                if ($key === null) {
                    $items[] = $value;
                } else {
                    $items[Type::toString($key)] = $value;
                }
            }
        } else {
            while ($item = $result->$method()) {
                $items[] = $item;
            }
        }

        return new static($items);
    }

    /**
     * Индексирует записи по указанному полю.
     * @param string $field Поле, по которому необходимо индексировать.
     * @return Arrays Текущий экземпляр.
     * @since 1.0.0
     */
    public function indexBy($field)
    {
        return $this->reindex(function ($key, $item) use (&$field) {
            return ArrayHelper::getValue($item, $field);
        });
    }

    /**
     * Сортирует массивы по указанному полю в указанном порядке.
     * @param string $field Поле сортировки.
     * @param int $order Порядок сортировки.
     * @return Arrays
     * @since 1.0.0
     */
    public function sortBy($field, $order = SORT_ASC)
    {
        $this->sort(function ($item1, $item2) use ($field, $order) {
            /**
             * @var array $item1
             * @var array $item2
             */

            $value1 = ArrayHelper::getValue($item1, $field);
            $value2 = ArrayHelper::getValue($item2, $field);
            $result = 0;

            if (!Type::isScalar($value1) || !Type::isScalar($value2))
                return $result;

            if (Type::isString($value1) || Type::isString($value2)) {
                $value1 = Type::toString($value1);
                $value2 = Type::toString($value2);

                $result = strcmp($value1, $value2);

                if ($result > 0) {
                    $result = 1;
                } else if ($result < 0) {
                    $result = -1;
                }
            } else {
                if ($value1 > $value2) {
                    $result = 1;
                } else if ($value1 < $value2) {
                    $result = -1;
                }
            }

            if ($order == SORT_DESC) {
                if ($result > 0) {
                    $result = -1;
                } else if ($result < 0) {
                    $result = 1;
                }
            }

            return $result;
        });

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return Type::isArray($item);
    }
}