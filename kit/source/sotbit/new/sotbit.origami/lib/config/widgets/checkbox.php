<?php
namespace Sotbit\Origami\Config\Widgets;

use Sotbit\Origami\Config\Widget;

class CheckBox extends Widget
{
	public function show()
	{
        $show = '
		<input type="hidden" name="' . $this->getCode() . '" value="N" />
		<input 
				type="checkbox" 
				name="' . $this->getCode() . '" 
				id="' . $this->getCode() . '" 
				value="Y"
				' . ($this->getCurrentValue() == 'Y' ? ' checked' : '');

        if ($this->settings['refresh'] == 'Y') {
            $show .= ' onchange="submit();"';
        }

        $show .= ' />';
        echo $show;
	}

}
