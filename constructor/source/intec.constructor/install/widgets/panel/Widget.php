<?php
namespace widgets\intec\constructor\panel;

class Widget extends \intec\constructor\structure\Widget
{
    public function getName()
    {
        return $this->getLanguage()->getMessage('name');
    }
}