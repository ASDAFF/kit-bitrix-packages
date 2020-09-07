<?php
namespace intec\constructor\models\build\template;

use intec\core\db\ActiveQuery;

/**
 * Class ContainerQuery
 * @package intec\constructor\models\build\template
 */
class ContainerQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function createCollection($items)
    {
        return new Containers($items);
    }
}