<?php
namespace widgets\intec\constructor\text;

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

class Widget extends \intec\constructor\structure\Widget
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getLanguage()->getMessage('name');
    }
}