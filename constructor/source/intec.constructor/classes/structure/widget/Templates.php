<?php
namespace intec\constructor\structure\widget;

use intec\constructor\base\Snippets;

/**
 * Class Templates
 * @package intec\constructor\structure\widget\Template
 */
class Templates extends Snippets
{
    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return $item instanceof Template;
    }
}