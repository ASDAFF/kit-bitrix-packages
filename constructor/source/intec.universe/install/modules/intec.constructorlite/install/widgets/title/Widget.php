<?php
namespace widgets\intec\constructor\title;

class Widget extends \intec\constructor\structure\Widget
{
    public function getName()
    {
        return $this->getLanguage()->getMessage('name');
    }
}