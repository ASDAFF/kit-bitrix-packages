<?php
namespace intec\core\collections;

use intec\core\base\Collection;
use intec\core\helpers\Type;

class Scalars extends Collection
{
    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return Type::isScalar($item);
    }
}