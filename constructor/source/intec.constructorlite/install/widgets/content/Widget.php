<?php
namespace widgets\intec\constructor\content;

class Widget extends \intec\constructor\structure\Widget
{
    public function getName()
    {
        return $this->getLanguage()->getMessage('name');
    }
}