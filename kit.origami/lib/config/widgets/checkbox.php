<?php
namespace Kit\Origami\Config\Widgets;

use Kit\Origami\Config\Widget;

class CheckBox extends Widget
{
	public function show()
	{
		echo '
		<input type="hidden" name="' . $this->getCode() . '" value="N" />
		<input 
				type="checkbox" 
				name="' . $this->getCode() . '" 
				id="' . $this->getCode() . '" 
				value="Y"
				' . ($this->getCurrentValue() == 'Y' ? ' checked' : '') . ' />';
	}

}

?>