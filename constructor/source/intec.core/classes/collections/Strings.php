<?php
namespace intec\core\collections;

use intec\core\base\Collection;
use intec\core\helpers\Type;

class Strings extends Collection
{
    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return Type::isString($item);
    }

    /**
     * Объеденяет строку.
     * @param string $glue
     * @return string
     */
    public function join($glue = '')
    {
        return implode($glue, $this->items);
    }
}