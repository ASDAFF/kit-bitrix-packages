<?php
namespace Kit\Origami\Config\Widgets;


use Kit\Origami\Config\Widget;

class Str extends Widget
{
	public function show()
	{
		echo '<input type="text" name="'.$this->getCode().'" value="'.$this->getCurrentValue().'" />';
	}
}
?>