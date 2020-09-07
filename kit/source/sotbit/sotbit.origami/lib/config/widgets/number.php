<?php

namespace Sotbit\Origami\Config\Widgets;

use Sotbit\Origami\Config\Widget;

class Number extends Widget
{
    public function show()
    {
        $curValue = $this->getCurrentValue();
        if (is_null($curValue)) {
            $curValue = 5;
        }
        echo '<input type="number" name="'.$this->getCode().'" value="'
            .$curValue.'" min="0"/>';
    }
}
