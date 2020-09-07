<?php
namespace intec\core\db;

use intec\core\base\Collection;
use intec\core\helpers\Type;

/**
 * Коллекция моделей.
 * Class ActiveRecords
 * @package intec\core\db
 * @since 1.0.0
 */
class ActiveRecords extends Collection
{
    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return $item instanceof ActiveRecord;
    }

    /**
     * Индексирует записи по указанному атрибуту.
     * @param string $attribute Атрибут, по которому необходимо индексировать.
     * @return ActiveRecords Текущий экземпляр.
     * @since 1.0.0
     */
    public function indexBy($attribute)
    {
        return $this->reindex(function ($key, $item) use (&$attribute) {
            return $item->{$attribute};
        });
    }

    /**
     * Сортирует записи по указанному полю в указанном порядке.
     * @param string $attribute Аттрибут сортировки.
     * @param int $order Порядок сортировки.
     * @return ActiveRecords
     * @since 1.0.0
     */
    public function sortBy($attribute, $order = SORT_ASC)
    {
        $this->sort(function ($item1, $item2) use ($attribute, $order) {
            /**
             * @var ActiveRecord $item1
             * @var ActiveRecord $item2
             */

            $value1 = $item1->getAttribute($attribute);
            $value2 = $item2->getAttribute($attribute);
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
}